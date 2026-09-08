import sys
import json
import os
import re
import ipaddress
import joblib
import pandas as pd

from urllib.parse import urlparse

from feature_extractor import extract_features, FEATURE_NAMES

# =========================================================
# PATHS
# =========================================================

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

MODEL_PATH = os.path.join(BASE_DIR, "models", "phishing_model.pkl")


# =========================================================
# SUSPICIOUS KEYWORDS
# =========================================================

SUSPICIOUS_KEYWORDS = [
    "login",
    "signin",
    "verify",
    "verification",
    "secure",
    "security",
    "account",
    "password",
    "update",
    "confirm",
    "payment",
    "billing",
    "bank",
    "wallet",
    "urgent",
    "suspended",
    "unlock",
    "recover",
]


# =========================================================
# URL SHORTENERS
# =========================================================

URL_SHORTENERS = [
    "bit.ly",
    "tinyurl.com",
    "t.co",
    "is.gd",
    "cutt.ly",
    "ow.ly",
    "buff.ly",
    "rebrand.ly",
]


# =========================================================
# COMMON BRANDS
#
# Used only for explanation.
# This does NOT affect the ML model probability.
# =========================================================

KNOWN_BRANDS = {
    "paypal": ["paypal.com"],
    "google": ["google.com"],
    "microsoft": ["microsoft.com", "live.com", "outlook.com"],
    "apple": ["apple.com", "icloud.com"],
    "amazon": ["amazon.com"],
    "facebook": ["facebook.com"],
    "instagram": ["instagram.com"],
    "netflix": ["netflix.com"],
    "linkedin": ["linkedin.com"],
    "github": ["github.com"],
}


# =========================================================
# DOMAIN CHECK
# =========================================================


def is_official_domain(hostname, official_domains):

    hostname = hostname.lower().strip(".")

    for domain in official_domains:

        domain = domain.lower()

        if hostname == domain:
            return True

        if hostname.endswith("." + domain):
            return True

    return False


# =========================================================
# NORMALIZE URL
# =========================================================


def normalize_for_analysis(url):

    url = str(url).strip()

    if not url.lower().startswith(("http://", "https://")):

        url = "http://" + url

    return url


# =========================================================
# HUMAN-READABLE EXPLANATION
# =========================================================


def analyze_url_indicators(url, features):

    risk_indicators = []
    positive_signals = []

    feature_values = dict(zip(FEATURE_NAMES, features))

    normalized_url = normalize_for_analysis(url)

    parsed = urlparse(normalized_url)

    hostname = (parsed.hostname or "").lower()

    full_url = normalized_url.lower()

    path_and_query = ((parsed.path or "") + "?" + (parsed.query or "")).lower()

    # =====================================================
    # 1. HTTPS
    # =====================================================

    if feature_values.get("IsHTTPS", 0) == 0:

        risk_indicators.append("The URL does not use HTTPS encryption.")

    else:

        positive_signals.append("The URL uses HTTPS.")

    # =====================================================
    # 2. IP ADDRESS
    # =====================================================

    try:

        ipaddress.ip_address(hostname)

        risk_indicators.append(
            "The URL uses an IP address instead of a normal domain name."
        )

    except ValueError:

        pass

    # =====================================================
    # 3. @ SYMBOL
    # =====================================================

    if "@" in full_url:

        risk_indicators.append(
            "The URL contains an @ symbol, which can make the real destination confusing."
        )

    # =====================================================
    # 4. URL LENGTH
    # =====================================================

    url_length = feature_values.get("URLLength", 0)

    if url_length >= 120:

        risk_indicators.append(
            "The URL is unusually long and may hide important destination information."
        )

    elif url_length >= 90:

        risk_indicators.append(
            "The URL is relatively long and should be checked carefully."
        )

    # =====================================================
    # 5. MULTIPLE SUBDOMAINS
    # =====================================================

    subdomains = feature_values.get("NoOfSubDomain", 0)

    if subdomains >= 3:

        risk_indicators.append(
            "The URL contains several subdomains, which may make the true domain harder to identify."
        )

    # =====================================================
    # 6. OBFUSCATION
    # =====================================================

    if feature_values.get("HasObfuscation", 0) == 1:

        risk_indicators.append(
            "Encoded or obfuscated characters were detected in the URL."
        )

    # =====================================================
    # 7. SUSPICIOUS KEYWORDS
    # =====================================================

    found_keywords = []

    for keyword in SUSPICIOUS_KEYWORDS:

        if keyword in path_and_query or keyword in hostname:

            found_keywords.append(keyword)

    found_keywords = list(dict.fromkeys(found_keywords))

    if len(found_keywords) >= 2:

        risk_indicators.append(
            "The URL contains multiple phishing-related words: "
            + ", ".join(found_keywords[:5])
            + "."
        )

    elif len(found_keywords) == 1:

        risk_indicators.append(
            "The URL contains a potentially sensitive word: " + found_keywords[0] + "."
        )

    # =====================================================
    # 8. BRAND IMPERSONATION
    # =====================================================

    for brand, official_domains in KNOWN_BRANDS.items():

        if brand in hostname:

            if not is_official_domain(hostname, official_domains):

                risk_indicators.append(
                    "The hostname contains the brand name '"
                    + brand
                    + "', but it is not using a recognized official "
                    + brand
                    + " domain."
                )

                break

    # =====================================================
    # 9. HYPHENS
    # =====================================================

    hyphen_count = hostname.count("-")

    if hyphen_count >= 3:

        risk_indicators.append(
            "The hostname contains several hyphens, which is sometimes seen in deceptive domains."
        )

    elif hyphen_count == 2:

        risk_indicators.append("The hostname contains multiple hyphens.")

    # =====================================================
    # 10. PUNYCODE
    # =====================================================

    if "xn--" in hostname:

        risk_indicators.append(
            "The hostname uses Punycode, which can sometimes be used to imitate familiar domain names."
        )

    # =====================================================
    # 11. URL SHORTENER
    # =====================================================

    if hostname in URL_SHORTENERS:

        risk_indicators.append(
            "The URL uses a shortening service, so the final destination is hidden."
        )

    # =====================================================
    # 12. LARGE NUMBER OF DIGITS
    # =====================================================

    digit_count = feature_values.get("NoOfDegitsInURL", 0)

    if digit_count >= 10:

        risk_indicators.append("The URL contains an unusually large number of digits.")

    # =====================================================
    # 13. MANY QUERY PARAMETERS
    # =====================================================

    equals_count = feature_values.get("NoOfEqualsInURL", 0)

    ampersand_count = feature_values.get("NoOfAmpersandInURL", 0)

    if equals_count >= 4 or ampersand_count >= 4:

        risk_indicators.append(
            "The URL contains many query parameters, making its destination structure more complex."
        )

    # =====================================================
    # REMOVE DUPLICATES
    # =====================================================

    risk_indicators = list(dict.fromkeys(risk_indicators))

    positive_signals = list(dict.fromkeys(positive_signals))

    return (risk_indicators, positive_signals)


# =========================================================
# PREDICT
# =========================================================


def predict_url(url):

    # =====================================================
    # MODEL CHECK
    # =====================================================

    if not os.path.exists(MODEL_PATH):

        return {"success": False, "message": "Machine learning model not found."}

    # =====================================================
    # LOAD TRAINED MODEL
    # =====================================================

    model_package = joblib.load(MODEL_PATH)

    model = model_package["model"]

    # =====================================================
    # EXTRACT FEATURES
    # =====================================================

    features = extract_features(url)

    feature_df = pd.DataFrame([features], columns=FEATURE_NAMES)

    # =====================================================
    # ML PROBABILITY
    #
    # Dataset:
    # 0 = phishing
    # 1 = legitimate
    # =====================================================

    probabilities = model.predict_proba(feature_df)[0]

    classes = list(model.classes_)

    phishing_index = classes.index(0)

    legitimate_index = classes.index(1)

    phishing_probability = float(probabilities[phishing_index])

    legitimate_probability = float(probabilities[legitimate_index])

    phishing_percent = round(phishing_probability * 100, 2)

    legitimate_percent = round(legitimate_probability * 100, 2)

    # =====================================================
    # CLASSIFICATION
    # =====================================================

    if phishing_probability >= 0.70:

        status = "Phishing"

        message = "The machine learning model classified " "this URL as high risk."

    elif phishing_probability >= 0.40:

        status = "Suspicious"

        message = "The machine learning model classified " "this URL as suspicious."

    else:

        status = "Safe"

        message = (
            "The machine learning model did not detect " "a high phishing probability."
        )

    # =====================================================
    # CONFIDENCE
    # =====================================================

    ai_confidence = round(max(phishing_probability, legitimate_probability) * 100, 2)

    # =====================================================
    # CONCRETE URL INDICATORS
    # =====================================================

    risk_indicators, positive_signals = analyze_url_indicators(url, features)

    # =====================================================
    # USER-FACING DETECTION DETAILS
    # =====================================================

    reasons = []

    # -----------------------------------------------------
    # SAFE
    # -----------------------------------------------------

    if status == "Safe":

        if len(risk_indicators) > 0:

            reasons.extend(risk_indicators)

            reasons.append(
                "Despite these indicators, the trained model "
                f"estimated only {phishing_percent:.2f}% "
                "phishing probability."
            )

        else:

            reasons.append(
                "No major suspicious URL structure indicators were identified."
            )

    # -----------------------------------------------------
    # SUSPICIOUS
    # -----------------------------------------------------

    elif status == "Suspicious":

        if len(risk_indicators) > 0:

            reasons.extend(risk_indicators)

        reasons.append(
            f"The model estimated a phishing probability "
            f"of {phishing_percent:.2f}%."
        )

        if len(risk_indicators) == 0:

            reasons.append(
                "No strong rule-based URL indicators were identified, "
                "so this result should be treated as a model-based warning."
            )

    # -----------------------------------------------------
    # PHISHING
    # -----------------------------------------------------

    elif status == "Phishing":

        if len(risk_indicators) > 0:

            reasons.extend(risk_indicators)

            reasons.append(
                f"The trained Random Forest model estimated "
                f"a phishing probability of {phishing_percent:.2f}%."
            )

        else:

            # IMPORTANT:
            # Do NOT invent fake reasons.

            reasons.append(
                "The URL does not show strong human-readable "
                "rule-based phishing indicators."
            )

            reasons.append(
                f"However, the trained Random Forest model estimated "
                f"a phishing probability of {phishing_percent:.2f}% "
                "from the combined URL features."
            )

            reasons.append(
                "Because the visible indicators do not strongly support "
                "the model prediction, this result may be a false positive "
                "and should be verified using additional reputation or "
                "domain checks."
            )

    # =====================================================
    # EVIDENCE LEVEL
    # =====================================================

    if status == "Phishing" and len(risk_indicators) >= 3:

        evidence_level = "Strong"

    elif status == "Phishing" and len(risk_indicators) >= 1:

        evidence_level = "Moderate"

    elif status == "Phishing":

        evidence_level = "Model-only"

    elif status == "Suspicious":

        evidence_level = "Moderate"

    else:

        evidence_level = "Low"

    # =====================================================
    # RETURN JSON
    # =====================================================

    return {
        "success": True,
        "url": url,
        "status": status,
        "risk_score": phishing_percent,
        "ai_confidence": ai_confidence,
        "phishing_probability": phishing_percent,
        "legitimate_probability": legitimate_percent,
        "message": message,
        # Existing detection.php uses this
        "reasons": reasons,
        # Useful later for improved UI
        "risk_indicators": risk_indicators,
        "positive_signals": positive_signals,
        "evidence_level": evidence_level,
        "model": "Random Forest",
    }


# =========================================================
# COMMAND LINE / PHP
# =========================================================

if __name__ == "__main__":

    try:

        if len(sys.argv) < 2:

            print(json.dumps({"success": False, "message": "No URL supplied."}))

            sys.exit(1)

        url = sys.argv[1]

        result = predict_url(url)

        # PHP needs JSON only
        print(json.dumps(result, ensure_ascii=False))

    except Exception as error:

        print(json.dumps({"success": False, "message": str(error)}))

        sys.exit(1)
