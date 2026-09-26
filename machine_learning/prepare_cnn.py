import pandas as pd
import numpy as np
import os

from sklearn.model_selection import train_test_split

# ============================================================
# PHISHGUARD AI
# CNN DATA PREPARATION
# ============================================================

print("=" * 70)
print("PHISHGUARD AI - CNN DATA PREPARATION")
print("=" * 70)


# ============================================================
# SETTINGS
# ============================================================

DATASET_PATH = os.path.join("dataset", "phishing_dataset.csv")

OUTPUT_DIR = os.path.join("dataset", "cnn_data")

RANDOM_STATE = 42

TEST_SIZE = 0.15
VALIDATION_SIZE = 0.15


# ============================================================
# CREATE OUTPUT DIRECTORY
# ============================================================

os.makedirs(OUTPUT_DIR, exist_ok=True)


# ============================================================
# CHECK DATASET
# ============================================================

if not os.path.exists(DATASET_PATH):

    print("\nERROR: Dataset not found.")

    print("Expected location:")

    print(os.path.abspath(DATASET_PATH))

    exit()


print("\nDataset:")
print(os.path.abspath(DATASET_PATH))


# ============================================================
# LOAD DATASET
# ============================================================

print("\nLoading dataset...")

df = pd.read_csv(DATASET_PATH)

print("Dataset loaded successfully.")


# ============================================================
# CHECK REQUIRED COLUMNS
# ============================================================

required_columns = ["URL", "label"]

for column in required_columns:

    if column not in df.columns:

        print(f"\nERROR: Required column '{column}' " f"was not found.")

        exit()


# ============================================================
# SELECT ONLY REQUIRED DATA
# ============================================================

data = df[["URL", "label"]].copy()


# ============================================================
# CLEAN DATA
# ============================================================

print("\nCleaning URLs...")


# Convert URL to string
data["URL"] = data["URL"].astype(str)


# Remove leading/trailing spaces
data["URL"] = data["URL"].str.strip()


# Remove empty URLs
data = data[data["URL"].str.len() > 0].copy()


# Remove missing labels
data = data[data["label"].notna()].copy()


# Convert labels to integer
data["label"] = data["label"].astype(int)


# ============================================================
# REMOVE DUPLICATE URLS
# ============================================================

before_duplicates = len(data)

data = data.drop_duplicates(subset=["URL"]).reset_index(drop=True)

after_duplicates = len(data)


print("\nDuplicate URLs removed:", before_duplicates - after_duplicates)


# ============================================================
# DISPLAY LABEL DISTRIBUTION
# ============================================================

print("\n" + "=" * 70)
print("LABEL DISTRIBUTION")
print("=" * 70)

print(data["label"].value_counts().sort_index())


print("\nLabel percentages:")

print((data["label"].value_counts(normalize=True).sort_index() * 100).round(2))


# ============================================================
# IMPORTANT LABEL INFORMATION
# ============================================================

print("\n" + "=" * 70)
print("LABEL INFORMATION")
print("=" * 70)

print("The dataset contains two labels:")

print(sorted(data["label"].unique()))

print("\nWe will keep the original numeric labels " "unchanged at this stage.")

print("This prevents accidentally reversing the " "dataset's original label meaning.")


# ============================================================
# TRAIN / TEMPORARY SPLIT
# ============================================================

print("\n" + "=" * 70)
print("CREATING DATA SPLITS")
print("=" * 70)


X = data["URL"]

y = data["label"]


X_train, X_temp, y_train, y_temp = train_test_split(
    X, y, test_size=(TEST_SIZE + VALIDATION_SIZE), random_state=RANDOM_STATE, stratify=y
)


# ============================================================
# VALIDATION / TEST SPLIT
# ============================================================

# Because the temporary set contains 30%:
#
# Validation = 15%
# Test       = 15%
#
# Therefore:
#
# 15 / 30 = 0.5

X_validation, X_test, y_validation, y_test = train_test_split(
    X_temp, y_temp, test_size=0.5, random_state=RANDOM_STATE, stratify=y_temp
)


# ============================================================
# CREATE DATAFRAMES
# ============================================================

train_df = pd.DataFrame({"URL": X_train.values, "label": y_train.values})


validation_df = pd.DataFrame({"URL": X_validation.values, "label": y_validation.values})


test_df = pd.DataFrame({"URL": X_test.values, "label": y_test.values})


# ============================================================
# RESET INDEX
# ============================================================

train_df = train_df.reset_index(drop=True)

validation_df = validation_df.reset_index(drop=True)

test_df = test_df.reset_index(drop=True)


# ============================================================
# DISPLAY SPLIT INFORMATION
# ============================================================

print("\nTraining samples:")
print(len(train_df))

print("\nValidation samples:")
print(len(validation_df))

print("\nTesting samples:")
print(len(test_df))


print("\nTotal samples:")

print(len(train_df) + len(validation_df) + len(test_df))


# ============================================================
# SAVE TRAINING DATA
# ============================================================

train_path = os.path.join(OUTPUT_DIR, "train.csv")

validation_path = os.path.join(OUTPUT_DIR, "validation.csv")

test_path = os.path.join(OUTPUT_DIR, "test.csv")


print("\nSaving files...")


train_df.to_csv(train_path, index=False)


validation_df.to_csv(validation_path, index=False)


test_df.to_csv(test_path, index=False)


# ============================================================
# SAVE DATA INFORMATION
# ============================================================

info_path = os.path.join(OUTPUT_DIR, "dataset_info.txt")


with open(info_path, "w", encoding="utf-8") as file:

    file.write("PHISHGUARD AI - CNN DATASET INFORMATION\n")

    file.write("========================================\n\n")

    file.write(f"Original rows: {len(df)}\n")

    file.write(f"Prepared rows: {len(data)}\n")

    file.write(f"Training rows: {len(train_df)}\n")

    file.write(f"Validation rows: {len(validation_df)}\n")

    file.write(f"Testing rows: {len(test_df)}\n\n")

    file.write("Columns used:\n")

    file.write("URL\n")

    file.write("label\n\n")

    file.write("Original labels were preserved.\n")


# ============================================================
# SHOW EXAMPLES
# ============================================================

print("\n" + "=" * 70)
print("TRAINING DATA EXAMPLES")
print("=" * 70)

print(train_df.head(10).to_string(index=False))


# ============================================================
# VERIFY LABEL DISTRIBUTION
# ============================================================

print("\n" + "=" * 70)
print("TRAINING LABEL DISTRIBUTION")
print("=" * 70)

print(train_df["label"].value_counts().sort_index())


print("\n" + "=" * 70)
print("VALIDATION LABEL DISTRIBUTION")
print("=" * 70)

print(validation_df["label"].value_counts().sort_index())


print("\n" + "=" * 70)
print("TEST LABEL DISTRIBUTION")
print("=" * 70)

print(test_df["label"].value_counts().sort_index())


# ============================================================
# COMPLETE
# ============================================================

print("\n" + "=" * 70)
print("CNN DATA PREPARATION COMPLETE")
print("=" * 70)

print("\nCreated folder:")

print(os.path.abspath(OUTPUT_DIR))


print("\nCreated files:")

print("1. train.csv")

print("2. validation.csv")

print("3. test.csv")

print("4. dataset_info.txt")


print("\nNext step:")
print("STEP 3 - Build and train the 1D CNN model.")

print("=" * 70)
