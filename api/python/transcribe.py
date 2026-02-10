import whisper
import sys
import os
import warnings

from indic_transliteration import sanscript
from indic_transliteration.sanscript import transliterate

sys.stdout.reconfigure(encoding='utf-8')
os.environ["PYTHONIOENCODING"] = "utf-8"
warnings.filterwarnings("ignore")

model = whisper.load_model("small")

def to_telugu_script(text):
    try:
        # Convert Devanagari → Telugu
        return transliterate(text, sanscript.DEVANAGARI, sanscript.TELUGU)
    except:
        return text

def transcribe_audio(file_path):
    if not os.path.isfile(file_path):
        return ""

    result = model.transcribe(
        file_path,
        language="te",
        task="transcribe",
        fp16=False,
        temperature=0.0,
        best_of=1,
        beam_size=5,
        condition_on_previous_text=False
    )

    raw_text = result["text"]
    telugu_text = to_telugu_script(raw_text)

    return telugu_text

if __name__ == "__main__":
    audio = sys.argv[1]
    text = transcribe_audio(audio)
    print(text.strip())
