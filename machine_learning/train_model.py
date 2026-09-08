import os
import sys
import joblib
import pandas as pd

from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix

from feature_extractor import extract_features, FEATURE_NAMES

# -------------------------------------------------
# File paths
# -------------------------------------------------

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

DATASET_PATH = os.path.join(BASE_DIR, "dataset", "phishing_dataset.csv")

MODEL_DIR = os.path.join(BASE_DIR, "models")

MODEL_PATH = os.path.join(MODEL_DIR, "phishing_model.pkl")


# -------------------------------------------------
# Check Dataset
# -------------------------------------------------

if not os.path.exists(DATASET_PATH):

    print("Dataset not found:")

    print(DATASET_PATH)

    sys.exit(1)


print("Loading dataset...")


df = pd.read_csv(DATASET_PATH)


# -------------------------------------------------
# Required columns
# -------------------------------------------------

if "URL" not in df.columns:

    raise ValueError("Dataset must contain a URL column.")


if "label" not in df.columns:

    raise ValueError("Dataset must contain a label column.")


# Remove empty rows

df = df[df["URL"].notna()].copy()


df = df[df["label"].isin([0, 1])].copy()


print("Total records:", len(df))


print("\nClass distribution:")

print(df["label"].value_counts())


# -------------------------------------------------
# Feature Extraction
# -------------------------------------------------

print("\nExtracting URL features...")


feature_rows = []


for index, url in enumerate(df["URL"]):

    try:

        feature_rows.append(extract_features(url))

    except Exception:

        feature_rows.append([0] * len(FEATURE_NAMES))

    if (index + 1) % 25000 == 0:

        print(f"Processed {index + 1} URLs")


X = pd.DataFrame(feature_rows, columns=FEATURE_NAMES)


y = df["label"].astype(int)


# -------------------------------------------------
# Train / Test Split
# -------------------------------------------------

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.20, random_state=42, stratify=y
)


print("\nTraining records:", len(X_train))

print("Testing records:", len(X_test))


# -------------------------------------------------
# Machine Learning Model
# -------------------------------------------------

print("\nTraining Random Forest model...")


model = RandomForestClassifier(
    n_estimators=200,
    max_depth=25,
    min_samples_split=4,
    min_samples_leaf=2,
    class_weight="balanced",
    random_state=42,
    n_jobs=-1,
)


model.fit(X_train, y_train)


# -------------------------------------------------
# Evaluation
# -------------------------------------------------

predictions = model.predict(X_test)


accuracy = accuracy_score(y_test, predictions)


print("\n==============================")

print("MODEL EVALUATION")

print("==============================")


print(f"\nAccuracy: {accuracy:.4f}")


print("\nClassification Report:")


print(
    classification_report(y_test, predictions, target_names=["Phishing", "Legitimate"])
)


print("Confusion Matrix:")


print(confusion_matrix(y_test, predictions))


# -------------------------------------------------
# Feature importance
# -------------------------------------------------

importance = pd.DataFrame(
    {"Feature": FEATURE_NAMES, "Importance": model.feature_importances_}
)


importance = importance.sort_values("Importance", ascending=False)


print("\nFeature Importance:")


print(importance.to_string(index=False))


# -------------------------------------------------
# Save Model
# -------------------------------------------------

os.makedirs(MODEL_DIR, exist_ok=True)


model_package = {
    "model": model,
    "feature_names": FEATURE_NAMES,
    # Dataset:
    # 0 = Phishing
    # 1 = Legitimate
    "phishing_label": 0,
    "legitimate_label": 1,
}


joblib.dump(model_package, MODEL_PATH)


print("\nModel saved successfully:")

print(MODEL_PATH)
