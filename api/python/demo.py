import os
import subprocess
import uuid

file_path = "YOUR_VIDEO_FILE_PATH_HERE"   # example placeholder

# Create a unique output dir for demucs results
run_id = str(uuid.uuid4())
output_dir = f"demucs_outputs/{run_id}"
os.makedirs(output_dir, exist_ok=True)

# Run demucs separation
subprocess.run([
    "demucs",
    "-o", output_dir,
    "-n", "mdx_extra",
    file_path
])

# demucs stores results in:   demucs_outputs/<uuid>/<model_name>/<basename>/
model_name = "mdx_extra"
file_base = os.path.basename(file_path)
demucs_folder = os.path.join(output_dir, model_name, file_base.replace(".mp4", ""))

# Final vocal file path from demucs
vocal_file = os.path.join(demucs_folder, "vocals.wav")

print("Vocal file created:", vocal_file)
