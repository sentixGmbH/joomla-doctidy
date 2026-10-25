"""Build the installable Joomla package: dist/plg_system_doctidy_<version>.zip

Paths inside the ZIP use '/', line endings are normalised to LF.
Usage:  python build.py
"""
import re
import zipfile
from pathlib import Path

ROOT = Path(__file__).resolve().parent
SRC = ROOT / "plugin"
DIST = ROOT / "dist"

version = re.search(r"<version>([^<]+)</version>", (SRC / "doctidy.xml").read_text(encoding="utf-8")).group(1)
DIST.mkdir(exist_ok=True)
target = DIST / f"plg_system_doctidy_{version}.zip"

with zipfile.ZipFile(target, "w", zipfile.ZIP_DEFLATED) as z:
    for f in sorted(SRC.rglob("*")):
        if f.is_file():
            z.writestr(f.relative_to(SRC).as_posix(), f.read_bytes().replace(b"\r\n", b"\n"))

print(target)
