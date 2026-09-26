import os
import sys
import json
import numpy as np
import tensorflow as tf

from tensorflow.keras.preprocessing.text import tokenizer_from_json
from tensorflow.keras.preprocessing.sequence import pad_sequences

# ============================================================
# PHISHGUARD AI
# CNN URL PREDICTION SYSTEM
# ============================================================

# ------------------------------------------------------------
# IMPORTANT:
# The CNN was trained using:
# URL -> Tokenizer -> sequence length 200 -> CNN
# ------------------------------------------------------------

MAX_SEQUENCE_LENGTH = 200

MODEL_PATH = os.path.join("models", "phishing_cnn.keras")

TOKENIZER_PATH = os.path.join("models", "cnn_tokenizer.json")


# ============================================================
# CHECK ARGUMENT
# ============================================================

if len(sys.argv) < 2:

    print(json.dumps({"success": False, "error": "No URL provided."}))

    sys.exit(1)


url = sys.argv[1].strip()


# ============================================================
# CHECK URL
# ============================================================

if not url:

    print(json.dumps({"success": False, "error": "URL is empty."}))

    sys.exit(1)


# ============================================================
# CHECK MODEL FILE
# ============================================================

if not os.path.exists(MODEL_PATH):

    print(
        json.dumps(
            {
                "success": False,
                "error": "CNN model file not found.",
                "path": os.path.abspath(MODEL_PATH),
            }
        )
    )

    sys.exit(1)


# ============================================================
# CHECK TOKENIZER FILE
# ============================================================

if not os.path.exists(TOKENIZER_PATH):

    print(
        json.dumps(
            {
                "success": False,
                "error": "CNN tokenizer file not found.",
                "path": os.path.abspath(TOKENIZER_PATH),
            }
        )
    )

    sys.exit(1)


try:

    # ========================================================
    # LOAD TOKENIZER
    # ========================================================

    with open(TOKENIZER_PATH, "r", encoding="utf-8") as file:

        tokenizer_json = file.read()

    tokenizer = tokenizer_from_json(tokenizer_json)

    # ========================================================
    # CONVERT URL TO SEQUENCE
    # ========================================================

    sequence = tokenizer.texts_to_sequences([url])

    padded_sequence = pad_sequences(
        sequence, maxlen=MAX_SEQUENCE_LENGTH, padding="post", truncating="post"
    )

    # ========================================================
    # LOAD CNN MODEL
    # ========================================================

    model = tf.keras.models.load_model(MODEL_PATH)

    # ========================================================
    # PREDICT
    # ========================================================

    probability = model.predict(padded_sequence, verbose=0)[0][0]

    probability = float(probability)

    # ========================================================
    # CNN LABEL MAPPING
    # ========================================================
    #
    # Your CNN dataset uses:
    #
    # 0 = Phishing
    # 1 = Legitimate
    #
    # Therefore:
    #
    # probability >= 0.5 -> Legitimate
    # probability <  0.5 -> Phishing
    #
    # ========================================================

    if probability >= 0.5:

        prediction = 1

        result = "LEGITIMATE"

        confidence = probability * 100

    else:

        prediction = 0

        result = "PHISHING"

        confidence = (1 - probability) * 100

    # ========================================================
    # RETURN JSON
    # ========================================================

    output = {
        "success": True,
        "url": url,
        "prediction": prediction,
        "result": result,
        "probability": round(probability, 6),
        "confidence": round(confidence, 2),
    }

    print(json.dumps(output, ensure_ascii=False))


except Exception as e:

    print(json.dumps({"success": False, "error": str(e)}))

    sys.exit(1)
