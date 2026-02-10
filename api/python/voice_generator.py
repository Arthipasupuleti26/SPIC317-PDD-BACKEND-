import sys
import asyncio
import edge_tts
import os

VOICE_MAP = {
    "Arjun": "en-IN-PrabhatNeural",
    "Ravi": "en-IN-NeerjaNeural",
    "Sarah": "en-US-AriaNeural"
}

async def generate_voice(text, voice_name, output_path):
    communicate = edge_tts.Communicate(
        text=text,
        voice=voice_name
    )
    await communicate.save(output_path)

if __name__ == "__main__":
    text = sys.argv[1]
    selected_voice = sys.argv[2]

    voice = VOICE_MAP.get(selected_voice, "en-US-AriaNeural")

    output_folder = "C:/xampp/htdocs/ai_dub/api/audio/outputs/voice"
    os.makedirs(output_folder, exist_ok=True)

    output_file = output_folder + "/output.mp3"

    asyncio.run(generate_voice(text, voice, output_file))

    print("VOICE_GENERATED_SUCCESS")
