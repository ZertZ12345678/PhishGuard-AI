import pandas as pd
import os

print("=" * 60)
print("PHISHGUARD AI - DATASET CHECK")
print("=" * 60)


# ==========================================
# DATASET PATH
# ==========================================

dataset_path = os.path.join("dataset", "phishing_dataset.csv")


# ==========================================
# CHECK DATASET EXISTS
# ==========================================

if not os.path.exists(dataset_path):

    print("\nERROR: Dataset not found.")

    print("\nPython is looking for:")
    print(os.path.abspath(dataset_path))

    print("\nPlease make sure the file is located at:")
    print(
        r"C:\xampp\htdocs\AI-Phishing-Detection-System"
        r"\machine_learning\dataset\phishing_dataset.csv"
    )

    exit()


# ==========================================
# LOAD DATASET
# ==========================================

print("\nDataset found:")
print(os.path.abspath(dataset_path))


df = pd.read_csv(dataset_path)


# ==========================================
# DATASET SIZE
# ==========================================

print("\n" + "=" * 60)
print("DATASET SIZE")
print("=" * 60)

print("Rows:", df.shape[0])
print("Columns:", df.shape[1])


# ==========================================
# COLUMN NAMES
# ==========================================

print("\n" + "=" * 60)
print("COLUMNS")
print("=" * 60)

for i, column in enumerate(df.columns):

    print(f"{i}: {column}")


# ==========================================
# FIRST 5 ROWS
# ==========================================

print("\n" + "=" * 60)
print("FIRST 5 ROWS")
print("=" * 60)

print(df.head())


# ==========================================
# DATA TYPES
# ==========================================

print("\n" + "=" * 60)
print("DATA TYPES")
print("=" * 60)

print(df.dtypes)


# ==========================================
# URL COLUMN
# ==========================================

print("\n" + "=" * 60)
print("URL COLUMN CHECK")
print("=" * 60)


if "URL" in df.columns:

    print("URL column found: YES")

    print("\nExample URLs:")

    print(df["URL"].head(10).to_string(index=False))

else:

    print("URL column found: NO")


# ==========================================
# LABEL COLUMN
# ==========================================

print("\n" + "=" * 60)
print("LABEL COLUMN CHECK")
print("=" * 60)


if "label" in df.columns:

    print("Label column found: YES")

    print("\nLabel values:")

    print(df["label"].value_counts())

    print("\nLabel percentages:")

    print((df["label"].value_counts(normalize=True) * 100).round(2))

else:

    print("Label column found: NO")


# ==========================================
# MISSING VALUES
# ==========================================

print("\n" + "=" * 60)
print("MISSING VALUES")
print("=" * 60)

print(df.isnull().sum().sort_values(ascending=False).head(10))


# ==========================================
# COMPLETE
# ==========================================

print("\n" + "=" * 60)
print("DATASET CHECK COMPLETE")
print("=" * 60)
