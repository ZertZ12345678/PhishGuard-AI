import os
import numpy as np
import pandas as pd
import tensorflow as tf

from tensorflow.keras.preprocessing.text import Tokenizer
from tensorflow.keras.preprocessing.sequence import pad_sequences

from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import (
    Embedding,
    Conv1D,
    GlobalMaxPooling1D,
    Dense,
    Dropout,
)

from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint

# ============================================================
# PHISHGUARD AI
# DEEP LEARNING CNN TRAINING
# ============================================================

print("=" * 70)
print("PHISHGUARD AI - DEEP LEARNING CNN")
print("=" * 70)


# ============================================================
# SETTINGS
# ============================================================

TRAIN_PATH = os.path.join("dataset", "cnn_data", "train.csv")

VALIDATION_PATH = os.path.join("dataset", "cnn_data", "validation.csv")

MODEL_DIR = "models"

MODEL_PATH = os.path.join(MODEL_DIR, "phishing_cnn.keras")

TOKENIZER_PATH = os.path.join(MODEL_DIR, "cnn_tokenizer.json")


# Maximum number of characters used by the tokenizer
MAX_VOCAB_SIZE = 200

# Maximum URL sequence length
MAX_SEQUENCE_LENGTH = 200

# CNN embedding size
EMBEDDING_DIM = 32

# Training settings
BATCH_SIZE = 256
EPOCHS = 15

RANDOM_SEED = 42


# ============================================================
# RANDOM SEED
# ============================================================

np.random.seed(RANDOM_SEED)
tf.random.set_seed(RANDOM_SEED)


# ============================================================
# CREATE MODEL DIRECTORY
# ============================================================

os.makedirs(MODEL_DIR, exist_ok=True)


# ============================================================
# CHECK FILES
# ============================================================

if not os.path.exists(TRAIN_PATH):

    print("\nERROR:")
    print("Training dataset was not found.")

    print(os.path.abspath(TRAIN_PATH))

    print("\nRun prepare_cnn.py first.")

    exit()


if not os.path.exists(VALIDATION_PATH):

    print("\nERROR:")
    print("Validation dataset was not found.")

    print(os.path.abspath(VALIDATION_PATH))

    print("\nRun prepare_cnn.py first.")

    exit()


# ============================================================
# LOAD DATA
# ============================================================

print("\nLoading training dataset...")

train_df = pd.read_csv(TRAIN_PATH)

print("Training rows:", len(train_df))


print("\nLoading validation dataset...")

validation_df = pd.read_csv(VALIDATION_PATH)

print("Validation rows:", len(validation_df))


# ============================================================
# CHECK COLUMNS
# ============================================================

required_columns = ["URL", "label"]

for column in required_columns:

    if column not in train_df.columns:

        print(f"\nERROR: '{column}' " "is missing from training data.")

        exit()

    if column not in validation_df.columns:

        print(f"\nERROR: '{column}' " "is missing from validation data.")

        exit()


# ============================================================
# CLEAN URL DATA
# ============================================================

train_df["URL"] = train_df["URL"].astype(str).str.strip()

validation_df["URL"] = validation_df["URL"].astype(str).str.strip()


# ============================================================
# LABEL DATA
# ============================================================

y_train = train_df["label"].astype("float32").values

y_validation = validation_df["label"].astype("float32").values


# ============================================================
# DISPLAY LABELS
# ============================================================

print("\n" + "=" * 70)
print("LABEL INFORMATION")
print("=" * 70)

print("Training labels:")

print(pd.Series(y_train).value_counts().sort_index())


print("\nValidation labels:")

print(pd.Series(y_validation).value_counts().sort_index())


# ============================================================
# CHARACTER TOKENIZER
# ============================================================

print("\n" + "=" * 70)
print("CREATING CHARACTER TOKENIZER")
print("=" * 70)


tokenizer = Tokenizer(
    num_words=MAX_VOCAB_SIZE, char_level=True, lower=False, filters=""
)


# IMPORTANT:
# Fit ONLY on training URLs.
#
# We do not fit the tokenizer on validation/test data.

tokenizer.fit_on_texts(train_df["URL"])


print("Character vocabulary size:", len(tokenizer.word_index))


# ============================================================
# CONVERT URLS TO SEQUENCES
# ============================================================

print("\nConverting URLs to sequences...")


X_train_sequences = tokenizer.texts_to_sequences(train_df["URL"])

X_validation_sequences = tokenizer.texts_to_sequences(validation_df["URL"])


# ============================================================
# PADDING
# ============================================================

print("Padding URL sequences...")


X_train = pad_sequences(
    X_train_sequences, maxlen=MAX_SEQUENCE_LENGTH, padding="post", truncating="post"
)


X_validation = pad_sequences(
    X_validation_sequences,
    maxlen=MAX_SEQUENCE_LENGTH,
    padding="post",
    truncating="post",
)


print("\nTraining input shape:", X_train.shape)

print("Validation input shape:", X_validation.shape)


# ============================================================
# VOCABULARY SIZE
# ============================================================

vocab_size = min(MAX_VOCAB_SIZE, len(tokenizer.word_index) + 1)


print("\nCNN vocabulary size:", vocab_size)


# ============================================================
# BUILD CNN MODEL
# ============================================================

print("\n" + "=" * 70)
print("BUILDING CNN MODEL")
print("=" * 70)


model = Sequential(
    [
        Embedding(
            input_dim=vocab_size,
            output_dim=EMBEDDING_DIM,
            input_length=MAX_SEQUENCE_LENGTH,
        ),
        Conv1D(filters=128, kernel_size=5, activation="relu"),
        GlobalMaxPooling1D(),
        Dense(64, activation="relu"),
        Dropout(0.5),
        Dense(1, activation="sigmoid"),
    ]
)


# ============================================================
# COMPILE MODEL
# ============================================================

model.compile(optimizer="adam", loss="binary_crossentropy", metrics=["accuracy"])


# ============================================================
# SHOW MODEL
# ============================================================

print("\nCNN architecture:")

model.summary()


# ============================================================
# CALLBACKS
# ============================================================

early_stopping = EarlyStopping(
    monitor="val_loss", patience=3, restore_best_weights=True
)


checkpoint = ModelCheckpoint(
    MODEL_PATH, monitor="val_accuracy", save_best_only=True, verbose=1
)


# ============================================================
# TRAIN CNN
# ============================================================

print("\n" + "=" * 70)
print("STARTING CNN TRAINING")
print("=" * 70)

print(f"\nEpochs: {EPOCHS}")

print(f"Batch size: {BATCH_SIZE}")

print(f"Maximum URL length: {MAX_SEQUENCE_LENGTH}")


history = model.fit(
    X_train,
    y_train,
    validation_data=(X_validation, y_validation),
    epochs=EPOCHS,
    batch_size=BATCH_SIZE,
    callbacks=[early_stopping, checkpoint],
    verbose=1,
)


# ============================================================
# SAVE FINAL MODEL
# ============================================================

print("\n" + "=" * 70)
print("SAVING CNN MODEL")
print("=" * 70)


model.save(MODEL_PATH)


print("\nCNN model saved to:")

print(os.path.abspath(MODEL_PATH))


# ============================================================
# SAVE TOKENIZER
# ============================================================

print("\nSaving tokenizer...")


tokenizer_json = tokenizer.to_json()


with open(TOKENIZER_PATH, "w", encoding="utf-8") as file:

    file.write(tokenizer_json)


print("Tokenizer saved to:")

print(os.path.abspath(TOKENIZER_PATH))


# ============================================================
# TRAINING RESULTS
# ============================================================

print("\n" + "=" * 70)
print("TRAINING RESULTS")
print("=" * 70)


best_train_accuracy = max(history.history["accuracy"])

best_validation_accuracy = max(history.history["val_accuracy"])


print("\nBest training accuracy:", f"{best_train_accuracy * 100:.2f}%")


print("Best validation accuracy:", f"{best_validation_accuracy * 100:.2f}%")


print("\nTraining epochs completed:", len(history.history["loss"]))


# ============================================================
# COMPLETE
# ============================================================

print("\n" + "=" * 70)
print("CNN TRAINING COMPLETE")
print("=" * 70)


print("\nCreated:")

print(MODEL_PATH)

print(TOKENIZER_PATH)


print("\nNext step:")

print("STEP 4 - Evaluate the CNN on the test dataset.")

print("=" * 70)
