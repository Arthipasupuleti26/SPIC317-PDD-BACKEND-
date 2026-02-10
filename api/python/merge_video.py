import sys
import os
import subprocess

video_path = sys.argv[1]
audio_path = sys.argv[2]

output_dir = "C:/xampp/htdocs/ai_dub/api/video/outputs/final"
os.makedirs(output_dir, exist_ok=True)

output_video = output_dir + "/dubbed_video.mp4"

cmd = [
    "ffmpeg",
    "-y",
    "-i", video_path,
    "-i", audio_path,
    "-map", "0:v",
    "-map", "1:a",
    "-c:v", "copy",
    "-shortest",
    output_video
]

subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)

print("VIDEO_MERGED_SUCCESS")
