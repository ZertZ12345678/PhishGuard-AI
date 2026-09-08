import re
import ipaddress
from urllib.parse import urlparse

FEATURE_NAMES = [
    "URLLength",
    "DomainLength",
    "IsDomainIP",
    "TLDLength",
    "NoOfSubDomain",
    "HasObfuscation",
    "NoOfObfuscatedChar",
    "ObfuscationRatio",
    "NoOfLettersInURL",
    "LetterRatioInURL",
    "NoOfDegitsInURL",
    "DegitRatioInURL",
    "NoOfEqualsInURL",
    "NoOfQMarkInURL",
    "NoOfAmpersandInURL",
    "NoOfOtherSpecialCharsInURL",
    "SpacialCharRatioInURL",
    "IsHTTPS",
]


def normalize_url(url):
    url = str(url).strip()

    if not url.startswith(("http://", "https://")):
        url = "http://" + url

    return url


def extract_features(url):

    url = normalize_url(url)

    parsed = urlparse(url)

    domain = parsed.hostname or ""

    # ----------------------------------------
    # URL Length
    # ----------------------------------------

    url_length = len(url)

    # ----------------------------------------
    # Domain Length
    # ----------------------------------------

    domain_length = len(domain)

    # ----------------------------------------
    # Domain is IP?
    # ----------------------------------------

    is_domain_ip = 0

    try:
        ipaddress.ip_address(domain)
        is_domain_ip = 1
    except ValueError:
        pass

    # ----------------------------------------
    # TLD
    # ----------------------------------------

    domain_parts = domain.split(".")

    tld = ""

    if len(domain_parts) > 1:
        tld = domain_parts[-1]

    tld_length = len(tld)

    # ----------------------------------------
    # Subdomains
    # ----------------------------------------

    if is_domain_ip:
        no_of_subdomain = 0

    elif len(domain_parts) > 2:
        no_of_subdomain = len(domain_parts) - 2

    else:
        no_of_subdomain = 0

    # ----------------------------------------
    # Obfuscation
    # Encoded URL characters such as %20
    # ----------------------------------------

    encoded_matches = re.findall(r"%[0-9A-Fa-f]{2}", url)

    no_of_obfuscated_char = len(encoded_matches)

    has_obfuscation = 1 if no_of_obfuscated_char > 0 else 0

    obfuscation_ratio = no_of_obfuscated_char / url_length if url_length > 0 else 0

    # ----------------------------------------
    # Letters
    # ----------------------------------------

    no_of_letters = sum(char.isalpha() for char in url)

    letter_ratio = no_of_letters / url_length if url_length > 0 else 0

    # ----------------------------------------
    # Digits
    # ----------------------------------------

    no_of_digits = sum(char.isdigit() for char in url)

    digit_ratio = no_of_digits / url_length if url_length > 0 else 0

    # ----------------------------------------
    # Individual special characters
    # ----------------------------------------

    no_of_equals = url.count("=")

    no_of_qmark = url.count("?")

    no_of_ampersand = url.count("&")

    # ----------------------------------------
    # Other special characters
    # ----------------------------------------

    special_chars = re.findall(r"[^A-Za-z0-9]", url)

    no_of_other_special_chars = (
        len(special_chars) - no_of_equals - no_of_qmark - no_of_ampersand
    )

    if no_of_other_special_chars < 0:
        no_of_other_special_chars = 0

    special_char_ratio = len(special_chars) / url_length if url_length > 0 else 0

    # ----------------------------------------
    # HTTPS
    # ----------------------------------------

    is_https = 1 if parsed.scheme.lower() == "https" else 0

    return [
        url_length,
        domain_length,
        is_domain_ip,
        tld_length,
        no_of_subdomain,
        has_obfuscation,
        no_of_obfuscated_char,
        obfuscation_ratio,
        no_of_letters,
        letter_ratio,
        no_of_digits,
        digit_ratio,
        no_of_equals,
        no_of_qmark,
        no_of_ampersand,
        no_of_other_special_chars,
        special_char_ratio,
        is_https,
    ]
