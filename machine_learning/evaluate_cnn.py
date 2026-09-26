import os
import numpy as np
import pandas as pd
import tensorflow as tf

from tensorflow.keras.preprocessing.text import tokenizer_from_json
from tensorflow.keras.preprocessing.sequence import pad_sequences

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
# CNN TEST EVALUATION
# ============================================================

print("=" * 70)
print("PHISHGUARD AI - CNN TEST EVALUATION")
print("=" * 70)


# ============================================================
# FILE PATHS
# ============================================================

TEST_PATH = os.path.join("dataset", "cnn_data", "test.csv")

MODEL_PATH = os.path.join("models", "phishing_cnn.keras")

TOKENIZER_PATH = os.path.join("models", "cnn_tokenizer.json")


MAX_SEQUENCE_LENGTH = 200


# ============================================================
# CHECK FILES
# ============================================================

print("\nChecking required files...")


for path in [TEST_PATH, MODEL_PATH, TOKENIZER_PATH]:

    if not os.path.exists(path):

        print("\nERROR: File not found:")
        print(os.path.abspath(path))

        exit()

    else:

        print("FOUND:", path)


# ============================================================
# LOAD TEST DATA
# ============================================================

print("\n" + "=" * 70)
print("LOADING TEST DATA")
print("=" * 70)


test_df = pd.read_csv(TEST_PATH)


print("Test samples:", len(test_df))


# ============================================================
# CHECK COLUMNS
# ============================================================

if "URL" not in test_df.columns:

    print("\nERROR: URL column not found.")

    exit()


if "label" not in test_df.columns:

    print("\nERROR: label column not found.")

    exit()


# ============================================================
# PREPARE TEST DATA
# ============================================================

test_df["URL"] = test_df["URL"].astype(str).str.strip()


X_test_text = test_df["URL"].values

y_test = test_df["label"].astype(int).values


# ============================================================
# LOAD TOKENIZER
# ============================================================

print("\nLoading tokenizer...")


with open(TOKENIZER_PATH, "r", encoding="utf-8") as file:

    tokenizer_json = file.read()


tokenizer = tokenizer_from_json(tokenizer_json)


print("Tokenizer loaded successfully.")


# ============================================================
# CONVERT URLS TO SEQUENCES
# ============================================================

print("\nConverting test URLs to sequences...")


X_test_sequences = tokenizer.texts_to_sequences(X_test_text)


# ============================================================
# PAD TEST SEQUENCES
# ============================================================

X_test = pad_sequences(
    X_test_sequences, maxlen=MAX_SEQUENCE_LENGTH, padding="post", truncating="post"
)


print("Test input shape:", X_test.shape)


# ============================================================
# LOAD CNN MODEL
# ============================================================

print("\n" + "=" * 70)
print("LOADING CNN MODEL")
print("=" * 70)


model = tf.keras.models.load_model(MODEL_PATH)


print("CNN model loaded successfully.")


# ============================================================
# MODEL EVALUATION
# ============================================================

print("\n" + "=" * 70)
print("RUNNING CNN ON TEST DATA")
print("=" * 70)


test_loss, test_accuracy = model.evaluate(X_test, y_test, batch_size=256, verbose=1)


# ============================================================
# PREDICTIONS
# ============================================================

print("\nGenerating predictions...")


probabilities = model.predict(X_test, batch_size=256, verbose=1)


probabilities = probabilities.ravel()


# Convert probability to class
#
# >= 0.5 → 1
# <  0.5 → 0

y_pred = (probabilities >= 0.5).astype(int)


# ============================================================
# METRICS
# ============================================================

accuracy = accuracy_score(y_test, y_pred)


precision = precision_score(y_test, y_pred, zero_division=0)


recall = recall_score(y_test, y_pred, zero_division=0)


f1 = f1_score(y_test, y_pred, zero_division=0)


# ============================================================
# DISPLAY RESULTS
# ============================================================

print("\n" + "=" * 70)
print("CNN TEST RESULTS")
print("=" * 70)


print(f"\nTest Loss:       {test_loss:.6f}")

print(f"Test Accuracy:   {accuracy * 100:.2f}%")

print(f"Precision:       {precision * 100:.2f}%")

print(f"Recall:          {recall * 100:.2f}%")

print(f"F1-Score:        {f1 * 100:.2f}%")


# ============================================================
# CONFUSION MATRIX
# ============================================================

print("\n" + "=" * 70)
print("CONFUSION MATRIX")
print("=" * 70)


cm = confusion_matrix(y_test, y_pred)


print(cm)


# ============================================================
# CLASSIFICATION REPORT
# ============================================================

print("\n" + "=" * 70)
print("CLASSIFICATION REPORT")
print("=" * 70)


print(classification_report(y_test, y_pred, digits=4, zero_division=0))


# ============================================================
# PREDICTION DISTRIBUTION
# ============================================================

print("\n" + "=" * 70)
print("PREDICTION DISTRIBUTION")
print("=" * 70)


unique_predictions, prediction_counts = np.unique(y_pred, return_counts=True)


for label_value, count in zip(unique_predictions, prediction_counts):

    print(f"Label {label_value}: {count}")


# ============================================================
# ACTUAL LABEL DISTRIBUTION
# ============================================================

print("\n" + "=" * 70)
print("ACTUAL TEST LABEL DISTRIBUTION")
print("=" * 70)


unique_actual, actual_counts = np.unique(y_test, return_counts=True)


for label_value, count in zip(unique_actual, actual_counts):

    print(f"Label {label_value}: {count}")


# ============================================================
# SAVE RESULTS
# ============================================================

results_path = os.path.join("models", "cnn_test_results.txt")


with open(results_path, "w", encoding="utf-8") as file:

    file.write("PHISHGUARD AI - CNN TEST RESULTS\n")

    file.write("================================\n\n")

    file.write(f"Test samples: {len(y_test)}\n")

    file.write(f"Test loss: {test_loss:.6f}\n")

    file.write(f"Accuracy: {accuracy * 100:.2f}%\n")

    file.write(f"Precision: {precision * 100:.2f}%\n")

    file.write(f"Recall: {recall * 100:.2f}%\n")

    file.write(f"F1-score: {f1 * 100:.2f}%\n\n")

    file.write("Confusion Matrix:\n")

    file.write(str(cm))

    file.write("\n\nClassification Report:\n")

    file.write(classification_report(y_test, y_pred, digits=4, zero_division=0))


# ============================================================
# COMPLETE
# ============================================================

print("\n" + "=" * 70)
print("CNN TEST EVALUATION COMPLETE")
print("=" * 70)


print("\nResults saved to:")

print(os.path.abspath(results_path))


print("\nNext step:")

print("STEP 5 - Compare CNN results with the existing Random Forest.")

print("=" * 70)
