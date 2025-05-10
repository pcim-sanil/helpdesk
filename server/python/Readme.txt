# istall python package to detect language.
$ sudo python3 -m pip install langid


<code>
import langid
# Restrict to only German (de), French (fr), and Arabic (ar)
langid.set_languages(['de', 'fr', 'ar'])
# Test the language classification
text = "Bonjour tout le monde"
lang, confidence = langid.classify(text)
print(f"Detected language: {lang} with confidence: {confidence}"
</code>