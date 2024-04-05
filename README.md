# BlueSpice Wiki Free Edition

## Build

```bash
# Download the latest codebase into _codebase/BlueSpice-free-latest.zip
wget \
	https://bluespice.com/filebase/bluespice-free/ \
	-O _codebase/BlueSpice-free-latest.zip

# Unzip the codebase into _codebase/w
unzip _codebase/BlueSpice-free-latest.zip -d _codebase/
mv _codebase/bluespice _codebase/w

# Build the image
docker build -t bluespice/wiki-free:latest .
```