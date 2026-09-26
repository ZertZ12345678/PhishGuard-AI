import os
import numpy as np
import pandas as pd
import tensorflow as tf

from tensorflow.keras.preprocessing.text import tokenizer_from_json
from tensorflow.keras.preprocessing.sequence import pad_sequences

# ============================================================
# PHISHGUARD AI
# EXPORT CNN TEST PREDICTIONS
# ============================================================

print("=" * 70)
print("PHISHGUARD AI - EXPORT CNN TEST PREDICTIONS")
print("=" * 70)


# ============================================================
# FILE PATHS
# ============================================================

TEST_PATH = os.path.join("dataset", "cnn_data", "test.csv")

MODEL_PATH = os.path.join("models", "phishing_cnn.keras")

TOKENIZER_PATH = os.path.join("models", "cnn_tokenizer.json")

OUTPUT_PATH = os.path.join("models", "cnn_test_predictions.csv")


MAX_SEQUENCE_LENGTH = 200


# ============================================================
# CHECK FILES
# ============================================================

print("\nChecking files...")


for path in [TEST_PATH, MODEL_PATH, TOKENIZER_PATH]:

    if not os.path.exists(path):

        print("\nERROR: File not found:")
        print(os.path.abspath(path))

        exit()

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
# PREPARE URLS
# ============================================================

test_df["URL"] = test_df["URL"].astype(str).str.strip()


# ============================================================
# LOAD TOKENIZER
# ============================================================

print("\nLoading tokenizer...")


with open(TOKENIZER_PATH, "r", encoding="utf-8") as file:

    tokenizer_json = file.read()


tokenizer = tokenizer_from_json(tokenizer_json)


print("Tokenizer loaded.")


# ============================================================
# CONVERT URLS
# ============================================================

print("\nConverting URLs...")


sequences = tokenizer.texts_to_sequences(test_df["URL"])


X_test = pad_sequences(
    sequences, maxlen=MAX_SEQUENCE_LENGTH, padding="post", truncating="post"
)


print("CNN input shape:", X_test.shape)


# ============================================================
# LOAD CNN
# ============================================================

print("\nLoading CNN model...")


model = tf.keras.models.load_model(MODEL_PATH)


print("CNN model loaded.")


# ============================================================
# PREDICT
# ============================================================

print("\nRunning CNN predictions...")


probabilities = model.predict(X_test, batch_size=256, verbose=1).ravel()


predictions = (probabilities >= 0.5).astype(int)


# ============================================================
# CREATE OUTPUT
# ============================================================

output_df = pd.DataFrame(
    {
        "URL": test_df["URL"],
        "actual_label": test_df["label"].astype(int),
        "cnn_probability": probabilities,
        "cnn_prediction": predictions,
    }
)


# ============================================================
# SAVE
# ============================================================

output_df.to_csv(OUTPUT_PATH, index=False)


print("\n" + "=" * 70)
print("CNN PREDICTIONS SAVED")
print("=" * 70)


print("\nFile:")

print(os.path.abspath(OUTPUT_PATH))


print("\nRows saved:", len(output_df))


print("\nFirst 5 predictions:")

print(output_df.head().to_string(index=False))


print("\n" + "=" * 70)
print("COMPLETE")
print("=" * 70)
