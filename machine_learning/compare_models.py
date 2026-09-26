import os
import joblib
import pandas as pd

from sklearn.metrics import (
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    confusion_matrix,
    classification_report,
)

# ============================================================
# PHISHGUARD AI
# FAIR RANDOM FOREST VS CNN COMPARISON
# ============================================================

print("=" * 70)
print("PHISHGUARD AI - RANDOM FOREST VS CNN")
print("=" * 70)


# ============================================================
# FILE PATHS
# ============================================================

ORIGINAL_DATASET_PATH = os.path.join("dataset", "phishing_dataset.csv")

TEST_PATH = os.path.join("dataset", "cnn_data", "test.csv")

MODEL_PATH = os.path.join("models", "phishing_model.pkl")

CNN_PREDICTIONS_PATH = os.path.join("models", "cnn_test_predictions.csv")

RESULTS_PATH = os.path.join("models", "rf_vs_cnn_results.txt")


# ============================================================
# CHECK FILES
# ============================================================

print("\nChecking required files...")

required_files = [ORIGINAL_DATASET_PATH, TEST_PATH, MODEL_PATH, CNN_PREDICTIONS_PATH]

for path in required_files:

    if not os.path.exists(path):

        print("\nERROR: File not found:")
        print(os.path.abspath(path))

        exit()

    print("FOUND:", path)


# ============================================================
# LOAD ORIGINAL DATASET
# ============================================================

print("\n" + "=" * 70)
print("LOADING ORIGINAL DATASET")
print("=" * 70)

original_df = pd.read_csv(ORIGINAL_DATASET_PATH)

print("Original dataset shape:", original_df.shape)


# ============================================================
# LOAD COMMON TEST DATA
# ============================================================

print("\n" + "=" * 70)
print("LOADING COMMON TEST DATA")
print("=" * 70)

test_df = pd.read_csv(TEST_PATH)

test_df["URL"] = test_df["URL"].astype(str).str.strip()

test_df["label"] = test_df["label"].astype(int)

print("Common test samples:", len(test_df))


# ============================================================
# CHECK TEST COLUMNS
# ============================================================

if "URL" not in test_df.columns:

    print("\nERROR: URL column not found.")

    exit()


if "label" not in test_df.columns:

    print("\nERROR: label column not found.")

    exit()


# ============================================================
# LOAD CNN PREDICTIONS
# ============================================================

print("\n" + "=" * 70)
print("LOADING CNN PREDICTIONS")
print("=" * 70)

cnn_df = pd.read_csv(CNN_PREDICTIONS_PATH)

print("CNN prediction rows:", len(cnn_df))


# ============================================================
# CHECK CNN COLUMNS
# ============================================================

required_cnn_columns = ["URL", "actual_label", "cnn_probability", "cnn_prediction"]

for column in required_cnn_columns:

    if column not in cnn_df.columns:

        print(f"\nERROR: CNN column '{column}' not found.")

        exit()


# ============================================================
# CHECK CNN ROW COUNT
# ============================================================

if len(cnn_df) != len(test_df):

    print("\nERROR:")

    print("CNN prediction count does not match " "the common test dataset.")

    print("Test rows:", len(test_df))

    print("CNN rows:", len(cnn_df))

    exit()


# ============================================================
# NORMALIZE CNN URL
# ============================================================

cnn_df["URL"] = cnn_df["URL"].astype(str).str.strip()

cnn_df["actual_label"] = cnn_df["actual_label"].astype(int)

cnn_df["cnn_prediction"] = cnn_df["cnn_prediction"].astype(int)


# ============================================================
# VERIFY CNN URL ORDER
# ============================================================

if not (test_df["URL"].values == cnn_df["URL"].values).all():

    print("\nERROR:")

    print("CNN prediction URLs do not match " "the common test dataset.")

    exit()


print("CNN URLs verified successfully.")


# ============================================================
# VERIFY CNN LABELS
# ============================================================

if not (test_df["label"].values == cnn_df["actual_label"].values).all():

    print("\nERROR:")

    print("CNN actual labels do not match " "the common test dataset.")

    exit()


print("CNN actual labels verified successfully.")


# ============================================================
# CNN PREDICTIONS
# ============================================================

y_test = test_df["label"].astype(int).values

y_pred_cnn = cnn_df["cnn_prediction"].astype(int).values


# ============================================================
# LOAD RANDOM FOREST
# ============================================================

print("\n" + "=" * 70)
print("LOADING RANDOM FOREST")
print("=" * 70)

model_data = joblib.load(MODEL_PATH)

print("Random Forest loaded successfully.")

print("\nModel contents:")

print(model_data.keys())


# ============================================================
# RF INFORMATION
# ============================================================

model = model_data["model"]

feature_names = model_data["feature_names"]

phishing_label = model_data.get("phishing_label")

legitimate_label = model_data.get("legitimate_label")


print("\nNumber of RF features:", len(feature_names))

print("Phishing label:", phishing_label)

print("Legitimate label:", legitimate_label)


# ============================================================
# PREPARE ORIGINAL DATASET
# ============================================================

print("\n" + "=" * 70)
print("PREPARING ORIGINAL DATASET")
print("=" * 70)

original_df["URL"] = original_df["URL"].astype(str).str.strip()

original_df["label"] = original_df["label"].astype(int)


# ============================================================
# CREATE OCCURRENCE NUMBER
#
# IMPORTANT:
# Some URLs appear multiple times in the dataset.
#
# We therefore match:
#
# URL + label + occurrence
#
# instead of URL alone.
# ============================================================

print("\nHandling duplicate URLs...")


original_df["_occurrence"] = original_df.groupby(["URL", "label"]).cumcount()


test_df["_occurrence"] = test_df.groupby(["URL", "label"]).cumcount()


print("Duplicate-aware matching key created.")


# ============================================================
# CHECK RF FEATURES
# ============================================================

missing_features = [
    feature for feature in feature_names if feature not in original_df.columns
]


if len(missing_features) > 0:

    print("\nERROR: Missing RF features " "from original dataset:")

    for feature in missing_features:

        print("-", feature)

    exit()


# ============================================================
# CREATE MATCHING KEY
# ============================================================

match_columns = ["URL", "label", "_occurrence"]


# ============================================================
# MATCH TEST DATA TO ORIGINAL DATASET
# ============================================================

print("\n" + "=" * 70)
print("MATCHING TEST ROWS")
print("=" * 70)


# Only keep rows needed for RF

rf_columns = match_columns + feature_names


rf_source_df = original_df[rf_columns].copy()


print("Original rows available:", len(rf_source_df))


# ============================================================
# MERGE
# ============================================================

merged_df = test_df[match_columns].merge(
    rf_source_df, on=match_columns, how="left", sort=False, indicator=True
)


# ============================================================
# CHECK MATCHES
# ============================================================

matched_count = (merged_df["_merge"] == "both").sum()


missing_count = (merged_df["_merge"] != "both").sum()


print("\nExpected test rows:", len(test_df))

print("Matched rows:", matched_count)

print("Missing rows:", missing_count)


if missing_count > 0:

    print("\nERROR:")

    print("Some test rows could not be matched " "to the original dataset.")

    missing_rows = merged_df[merged_df["_merge"] != "both"]

    print("\nFirst missing rows:")

    print(missing_rows[match_columns].head(10).to_string(index=False))

    exit()


print("\nAll test rows matched successfully.")


# ============================================================
# REMOVE MERGE COLUMN
# ============================================================

merged_df = merged_df.drop(columns=["_merge"])


# ============================================================
# PREPARE RF FEATURES
# ============================================================

print("\n" + "=" * 70)
print("PREPARING RANDOM FOREST FEATURES")
print("=" * 70)


X_test_rf = merged_df[feature_names].copy()


print("RF test shape:", X_test_rf.shape)


print("Expected shape:", (len(test_df), len(feature_names)))


# ============================================================
# CHECK MISSING VALUES
# ============================================================

missing_value_count = X_test_rf.isnull().sum().sum()


print("\nMissing RF feature values:", missing_value_count)


if missing_value_count > 0:

    print("\nERROR:")

    print("RF test features contain missing values.")

    exit()


# ============================================================
# RANDOM FOREST PREDICTION
# ============================================================

print("\n" + "=" * 70)
print("RUNNING RANDOM FOREST PREDICTIONS")
print("=" * 70)


y_pred_rf = model.predict(X_test_rf)


y_pred_rf = y_pred_rf.astype(int)


print("Random Forest predictions complete.")


# ============================================================
# CHECK PREDICTION COUNT
# ============================================================

if len(y_pred_rf) != len(y_test):

    print("\nERROR:")

    print("Random Forest prediction count " "does not match test data.")

    exit()


# ============================================================
# METRICS FUNCTION
# ============================================================


def calculate_metrics(y_true, y_pred, phishing_label):

    accuracy = accuracy_score(y_true, y_pred)

    precision = precision_score(
        y_true, y_pred, pos_label=phishing_label, zero_division=0
    )

    recall = recall_score(y_true, y_pred, pos_label=phishing_label, zero_division=0)

    f1 = f1_score(y_true, y_pred, pos_label=phishing_label, zero_division=0)

    return (accuracy, precision, recall, f1)


# ============================================================
# CALCULATE RANDOM FOREST METRICS
# ============================================================

rf_accuracy, rf_precision, rf_recall, rf_f1 = calculate_metrics(
    y_test, y_pred_rf, phishing_label
)


# ============================================================
# CALCULATE CNN METRICS
# ============================================================

cnn_accuracy, cnn_precision, cnn_recall, cnn_f1 = calculate_metrics(
    y_test, y_pred_cnn, phishing_label
)


# ============================================================
# FAIR MODEL COMPARISON
# ============================================================

print("\n" + "=" * 70)
print("FAIR MODEL COMPARISON")
print("=" * 70)


print("\nCommon test samples:", len(y_test))


print("\nLabel mapping:")

print("Phishing:", phishing_label)

print("Legitimate:", legitimate_label)


print(f"\n{'Metric':<20}" f"{'Random Forest':>18}" f"{'CNN':>18}")


print("-" * 56)


print(
    f"{'Accuracy':<20}" f"{rf_accuracy * 100:>17.2f}%" f"{cnn_accuracy * 100:>17.2f}%"
)


print(
    f"{'Phishing Precision':<20}"
    f"{rf_precision * 100:>17.2f}%"
    f"{cnn_precision * 100:>17.2f}%"
)


print(
    f"{'Phishing Recall':<20}"
    f"{rf_recall * 100:>17.2f}%"
    f"{cnn_recall * 100:>17.2f}%"
)


print(f"{'Phishing F1-Score':<20}" f"{rf_f1 * 100:>17.2f}%" f"{cnn_f1 * 100:>17.2f}%")


# ============================================================
# RANDOM FOREST CONFUSION MATRIX
# ============================================================

print("\n" + "=" * 70)
print("RANDOM FOREST CONFUSION MATRIX")
print("=" * 70)


rf_cm = confusion_matrix(y_test, y_pred_rf, labels=[phishing_label, legitimate_label])


print(rf_cm)


# ============================================================
# CNN CONFUSION MATRIX
# ============================================================

print("\n" + "=" * 70)
print("CNN CONFUSION MATRIX")
print("=" * 70)


cnn_cm = confusion_matrix(y_test, y_pred_cnn, labels=[phishing_label, legitimate_label])


print(cnn_cm)


# ============================================================
# RANDOM FOREST CLASSIFICATION REPORT
# ============================================================

print("\n" + "=" * 70)
print("RANDOM FOREST CLASSIFICATION REPORT")
print("=" * 70)


rf_report = classification_report(
    y_test,
    y_pred_rf,
    labels=[phishing_label, legitimate_label],
    target_names=["Phishing", "Legitimate"],
    digits=4,
    zero_division=0,
)


print(rf_report)


# ============================================================
# CNN CLASSIFICATION REPORT
# ============================================================

print("\n" + "=" * 70)
print("CNN CLASSIFICATION REPORT")
print("=" * 70)


cnn_report = classification_report(
    y_test,
    y_pred_cnn,
    labels=[phishing_label, legitimate_label],
    target_names=["Phishing", "Legitimate"],
    digits=4,
    zero_division=0,
)


print(cnn_report)


# ============================================================
# SAVE RESULTS
# ============================================================

print("\n" + "=" * 70)
print("SAVING COMPARISON RESULTS")
print("=" * 70)


with open(RESULTS_PATH, "w", encoding="utf-8") as file:

    file.write("PHISHGUARD AI\n")

    file.write("RANDOM FOREST VS CNN\n")

    file.write("====================\n\n")

    file.write(f"Common test samples: " f"{len(y_test)}\n\n")

    file.write(f"Phishing label: " f"{phishing_label}\n")

    file.write(f"Legitimate label: " f"{legitimate_label}\n\n")

    file.write("MODEL COMPARISON\n")

    file.write("----------------\n")

    file.write(f"Random Forest Accuracy: " f"{rf_accuracy * 100:.2f}%\n")

    file.write(f"Random Forest Phishing Precision: " f"{rf_precision * 100:.2f}%\n")

    file.write(f"Random Forest Phishing Recall: " f"{rf_recall * 100:.2f}%\n")

    file.write(f"Random Forest Phishing F1-Score: " f"{rf_f1 * 100:.2f}%\n\n")

    file.write(f"CNN Accuracy: " f"{cnn_accuracy * 100:.2f}%\n")

    file.write(f"CNN Phishing Precision: " f"{cnn_precision * 100:.2f}%\n")

    file.write(f"CNN Phishing Recall: " f"{cnn_recall * 100:.2f}%\n")

    file.write(f"CNN Phishing F1-Score: " f"{cnn_f1 * 100:.2f}%\n\n")

    file.write("RANDOM FOREST CONFUSION MATRIX\n")

    file.write(str(rf_cm))

    file.write("\n\n")

    file.write("CNN CONFUSION MATRIX\n")

    file.write(str(cnn_cm))

    file.write("\n\n")

    file.write("RANDOM FOREST CLASSIFICATION REPORT\n")

    file.write(rf_report)

    file.write("\n\n")

    file.write("CNN CLASSIFICATION REPORT\n")

    file.write(cnn_report)


# ============================================================
# COMPLETE
# ============================================================

print("\n" + "=" * 70)
print("MODEL COMPARISON COMPLETE")
print("=" * 70)


print("\nResults saved to:")

print(os.path.abspath(RESULTS_PATH))


print("\nCommon test dataset:", len(y_test), "URLs")


print("\nNo model was retrained.")


print("\nNext step:")

print("STEP 7 - Build the final URL prediction system.")


print("=" * 70)
