import pandas as pd
import joblib

from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix

# =========================
# 1. Load Dataset
# =========================

data = pd.read_csv("dataset/phishing_dataset.csv")

print("Dataset loaded")
print("Dataset shape:", data.shape)


# =========================
# 2. Load Existing Model
# =========================

model_data = joblib.load("models/phishing_model.pkl")

print("\nModel file loaded")

print("Model content:")
print(model_data.keys())


# Get actual classifier
model = model_data["model"]

# Get features used during training
feature_names = model_data["feature_names"]

print("\nNumber of trained features:", len(feature_names))


# =========================
# 3. Prepare Features
# =========================

# Separate label

# Change label name if your dataset uses another name
X = data.drop("label", axis=1)
y = data["label"]


# Select only features used by the trained model

missing_features = []

for feature in feature_names:
    if feature not in X.columns:
        missing_features.append(feature)


if len(missing_features) > 0:
    print("\nMissing features:")
    print(missing_features)
    raise Exception("Dataset does not contain all trained features")


X = X[feature_names]


# =========================
# 4. Split Test Data
# =========================

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)


print("\nTesting data size:", X_test.shape)


# =========================
# 5. Predict
# =========================

print("\nRunning prediction...")

y_pred = model.predict(X_test)


# =========================
# 6. Evaluation
# =========================

accuracy = accuracy_score(y_test, y_pred)


print("\n======================")
print("MODEL PERFORMANCE")
print("======================")

print(f"Accuracy: {accuracy * 100:.2f}%")


print("\nClassification Report:")
print(classification_report(y_test, y_pred))


print("\nConfusion Matrix:")
print(confusion_matrix(y_test, y_pred))
