echo "Resizing "$1" to the necessary sizes";
# 360
# 520
# 768
# 980
# 1170
# 2000

# https://www.imagemagick.org/Usage/resize/
# dimensions are x by y, followed by ^ to indicate that the resized image should _cover_ that rather than fit _within_ the dimensions.
convert $1 -resize 360x360^ search-360x360.jpg
convert $1 -resize 520x520^ search-520x520.jpg
convert $1 -resize 768x768^ search-768x768.jpg
convert $1 -resize 980x980^ search-980x980.jpg
convert $1 -resize 1170x1170^ search-1170x1170.jpg
convert $1 -resize 2000x2000^ search-2000x2000.jpg
