import langid
import sys


# Get the text to analyze from command-line arguments
text = sys.argv[1]

# Get allowed languages from command-line arguments, or use default
if len(sys.argv) > 2:
    allowed_languages = sys.argv[2].split(',')
else:
    allowed_languages = ['en']

langid.set_languages(allowed_languages)

# Detect language
lang, confidence = langid.classify(text)

# Print the result in a format PHP can read (JSON or simple string)
print(f"{lang}")