import sys
import json
import re
import ipaddress

from urllib.parse import urlparse

import pandas as pd
import joblib

# ============================================================
# PHISHGUARD AI
# RANDOM FOREST REAL-TIME URL PREDICTION
# ============================================================


MODEL_PATH = "models/phishing_model.pkl"


# ============================================================
# 1. GET URL
# ============================================================

if len(sys.argv) < 2:

    print(json.dumps({"success": False, "message": "No URL was provided."}))

    sys.exit(1)


url = sys.argv[1].strip()


if url == "":

    print(json.dumps({"success": False, "message": "Please enter a URL."}))

    sys.exit(1)


# ============================================================
# 2. NORMALIZE URL
# ============================================================

if not re.match(r"^https?://", url, re.IGNORECASE):

    url = "https://" + url


# ============================================================
# 3. VALIDATE URL
# ============================================================

try:

    parsed = urlparse(url)

except Exception as e:

    print(
        json.dumps(
            {"success": False, "message": "Unable to parse URL.", "error": str(e)}
        )
    )

    sys.exit(1)


if not parsed.netloc:

    print(json.dumps({"success": False, "message": "Invalid URL."}))

    sys.exit(1)


# ============================================================
# 4. EXTRACT DOMAIN
# ============================================================

domain = parsed.netloc


# Remove username/password

if "@" in domain:

    domain = domain.split("@")[-1]


# Remove port

if ":" in domain:

    domain = domain.split(":")[0]


domain = domain.lower()


# ============================================================
# 5. URL LENGTH
# ============================================================

URLLength = len(url)


# ============================================================
# 6. DOMAIN LENGTH
# ============================================================

DomainLength = len(domain)


# ============================================================
# 7. IS DOMAIN IP
# ============================================================

IsDomainIP = 0


try:

    ipaddress.ip_address(domain)

    IsDomainIP = 1

except ValueError:

    IsDomainIP = 0


# ============================================================
# 8. TLD LENGTH
# ============================================================

if "." in domain:

    tld = domain.split(".")[-1]

else:

    tld = ""


TLDLength = len(tld)


# ============================================================
# 9. NUMBER OF SUBDOMAINS
# ============================================================

domain_parts = domain.split(".")


if len(domain_parts) > 2:

    NoOfSubDomain = len(domain_parts) - 2

else:

    NoOfSubDomain = 0


# ============================================================
# 10. OBFUSCATION
# ============================================================

obfuscation_patterns = [r"%[0-9a-fA-F]{2}", r"\\x[0-9a-fA-F]{2}", r"0x[0-9a-fA-F]+"]


NoOfObfuscatedChar = 0


for pattern in obfuscation_patterns:

    matches = re.findall(pattern, url)

    NoOfObfuscatedChar += len(matches)


# @ can also indicate obfuscation

NoOfObfuscatedChar += url.count("@")


if NoOfObfuscatedChar > 0:

    HasObfuscation = 1

else:

    HasObfuscation = 0


# ============================================================
# 11. OBFUSCATION RATIO
# ============================================================

if URLLength > 0:

    ObfuscationRatio = NoOfObfuscatedChar / URLLength

else:

    ObfuscationRatio = 0


# ============================================================
# 12. NUMBER OF LETTERS
# ============================================================

NoOfLettersInURL = len(re.findall(r"[A-Za-z]", url))


# ============================================================
# 13. LETTER RATIO
# ============================================================

if URLLength > 0:

    LetterRatioInURL = NoOfLettersInURL / URLLength

else:

    LetterRatioInURL = 0


# ============================================================
# 14. NUMBER OF DIGITS
# ============================================================

NoOfDegitsInURL = len(re.findall(r"[0-9]", url))


# ============================================================
# 15. DIGIT RATIO
# ============================================================

if URLLength > 0:

    DegitRatioInURL = NoOfDegitsInURL / URLLength

else:

    DegitRatioInURL = 0


# ============================================================
# 16. EQUALS
# ============================================================

NoOfEqualsInURL = url.count("=")


# ============================================================
# 17. QUESTION MARK
# ============================================================

NoOfQMarkInURL = url.count("?")


# ============================================================
# 18. AMPERSAND
# ============================================================

NoOfAmpersandInURL = url.count("&")


# ============================================================
# 19. OTHER SPECIAL CHARACTERS
# ============================================================

special_characters = re.findall(r"[^A-Za-z0-9]", url)


NoOfOtherSpecialCharsInURL = len(special_characters)


# ============================================================
# 20. SPECIAL CHARACTER RATIO
# ============================================================

if URLLength > 0:

    SpacialCharRatioInURL = NoOfOtherSpecialCharsInURL / URLLength

else:

    SpacialCharRatioInURL = 0


# ============================================================
# 21. HTTPS
# ============================================================

if parsed.scheme.lower() == "https":

    IsHTTPS = 1

else:

    IsHTTPS = 0


# ============================================================
# 22. CREATE FEATURE DATAFRAME
# ============================================================

features = {
    "URLLength": URLLength,
    "DomainLength": DomainLength,
    "IsDomainIP": IsDomainIP,
    "TLDLength": TLDLength,
    "NoOfSubDomain": NoOfSubDomain,
    "HasObfuscation": HasObfuscation,
    "NoOfObfuscatedChar": NoOfObfuscatedChar,
    "ObfuscationRatio": ObfuscationRatio,
    "NoOfLettersInURL": NoOfLettersInURL,
    "LetterRatioInURL": LetterRatioInURL,
    "NoOfDegitsInURL": NoOfDegitsInURL,
    "DegitRatioInURL": DegitRatioInURL,
    "NoOfEqualsInURL": NoOfEqualsInURL,
    "NoOfQMarkInURL": NoOfQMarkInURL,
    "NoOfAmpersandInURL": NoOfAmpersandInURL,
    "NoOfOtherSpecialCharsInURL": NoOfOtherSpecialCharsInURL,
    "SpacialCharRatioInURL": SpacialCharRatioInURL,
    "IsHTTPS": IsHTTPS,
}


X = pd.DataFrame([features])


# ============================================================
# 23. LOAD RANDOM FOREST MODEL
# ============================================================

try:

    model_data = joblib.load(MODEL_PATH)

except Exception as e:

    print(
        json.dumps(
            {
                "success": False,
                "message": "Unable to load Random Forest model.",
                "error": str(e),
            }
        )
    )

    sys.exit(1)


# ============================================================
# 24. GET TRAINED MODEL
# ============================================================

try:

    model = model_data["model"]

    feature_names = model_data["feature_names"]

    phishing_label = model_data["phishing_label"]

    legitimate_label = model_data["legitimate_label"]

except Exception as e:

    print(
        json.dumps(
            {
                "success": False,
                "message": "Invalid Random Forest model file.",
                "error": str(e),
            }
        )
    )

    sys.exit(1)


# ============================================================
# 25. CHECK FEATURES
# ============================================================

missing_features = [feature for feature in feature_names if feature not in X.columns]


if missing_features:

    print(
        json.dumps(
            {
                "success": False,
                "message": "Required Random Forest features are missing.",
                "missing_features": missing_features,
            }
        )
    )

    sys.exit(1)


# ============================================================
# 26. USE EXACT TRAINING FEATURE ORDER
# ============================================================

X = X[feature_names]


# ============================================================
# 27. RANDOM FOREST PREDICTION
# ============================================================

try:

    prediction = model.predict(X)[0]

except Exception as e:

    print(
        json.dumps(
            {
                "success": False,
                "message": "Random Forest prediction failed.",
                "error": str(e),
            }
        )
    )

    sys.exit(1)


# ============================================================
# 28. GET PROBABILITY
# ============================================================

try:

    probabilities = model.predict_proba(X)[0]

    classes = model.classes_

    probability_map = dict(zip(classes, probabilities))

    prediction_probability = float(probability_map.get(prediction, 0))

except Exception as e:

    print(
        json.dumps(
            {
                "success": False,
                "message": "Unable to calculate Random Forest probability.",
                "error": str(e),
            }
        )
    )

    sys.exit(1)


# ============================================================
# 29. CONFIDENCE
# ============================================================

confidence = prediction_probability * 100


confidence = max(0, min(100, confidence))


# ============================================================
# 30. CONVERT MODEL LABEL TO WEBSITE RESULT
# ============================================================
#
# Your existing model:
#
# phishing_label   = 0
# legitimate_label = 1
#
# Website:
#
# 0 -> Phishing
# 1 -> Safe
# ============================================================

if prediction == phishing_label:

    result = "Phishing"

elif prediction == legitimate_label:

    result = "Safe"

else:

    # Fallback based on model label

    result = "Phishing"


# ============================================================
# 31. OUTPUT
# ============================================================

output = {
    "success": True,
    "model": "Random Forest",
    "url": url,
    "prediction": int(prediction),
    "result": result,
    "probability": round(prediction_probability, 6),
    "confidence": round(confidence, 2),
}


# ============================================================
# 32. RETURN JSON ONLY
# ============================================================

print(json.dumps(output, ensure_ascii=False))
