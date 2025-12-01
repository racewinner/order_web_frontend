var c = document.getElementById('canv'),
    ctx2d = c.getContext("2d");
var w = c.width = window.innerWidth,
    h = c.height = window.innerHeight;

// Christmas festival background image
var bgImage = new Image();
bgImage.crossOrigin = "anonymous";

// You can use a local image or a URL
// Option 1: Use a local image (place it in public/assets/images/)
// bgImage.src = '/assets/images/christmas-background.jpg';

// Option 2: Use a festive image from a CDN or URL
bgImage.src = '/assets/images/xmas/xmas-01.png';

// Fallback: Create a festive gradient background if image fails to load
var imageLoaded = false;

bgImage.onload = function() {
    imageLoaded = true;
    Snowy(); // Start snow effect after image loads
};

bgImage.onerror = function() {
    console.warn('Background image failed to load, using gradient fallback');
    imageLoaded = false;
    Snowy(); // Start snow effect with gradient fallback
};

function drawBackground() {
    if (imageLoaded && bgImage.complete) {
        // Draw the Christmas image, scaled to cover the canvas
        var imgAspect = bgImage.width / bgImage.height;
        var canvasAspect = w / h;
        
        var drawWidth, drawHeight, drawX, drawY;
        
        if (imgAspect > canvasAspect) {
            // Image is wider - fit to height
            drawHeight = h;
            drawWidth = h * imgAspect;
            drawX = (w - drawWidth) / 2;
            drawY = 0;
        } else {
            // Image is taller - fit to width
            drawWidth = w;
            drawHeight = w / imgAspect;
            drawX = 0;
            drawY = (h - drawHeight) / 2;
        }
        
        ctx2d.drawImage(bgImage, drawX, drawY, drawWidth, drawHeight);
    } else {
        // Draw a festive gradient background as fallback
        var gradient = ctx2d.createLinearGradient(0, 0, 0, h);
        gradient.addColorStop(0, '#0a1929'); // Dark blue at top
        gradient.addColorStop(0.5, '#1a2f4a'); // Medium blue in middle
        gradient.addColorStop(1, '#0d1b2a'); // Darker blue at bottom
        
        ctx2d.fillStyle = gradient;
        ctx2d.fillRect(0, 0, w, h);
        
        // Add some festive stars/twinkles
        ctx2d.fillStyle = 'rgba(255, 255, 255, 0.8)';
        for (var i = 0; i < 50; i++) {
            var x = Math.random() * w;
            var y = Math.random() * h * 0.3; // Stars in upper third
            var size = Math.random() * 2;
            ctx2d.beginPath();
            ctx2d.arc(x, y, size, 0, Math.PI * 2);
            ctx2d.fill();
        }
    }
}

function Snowy() {
    var snow, arr = [];
    var num = 600,
        tsc = 1,
        sp = 1;
    var sc = 1.3,
        t = 0,
        mv = 20,
        min = 1;
    for (var i = 0; i < num; ++i) {
        snow = new Flake();
        snow.y = Math.random() * (h + 50);
        snow.x = Math.random() * w;
        snow.t = Math.random() * (Math.PI * 2);
        snow.sz = (100 / (10 + (Math.random() * 100))) * sc;
        snow.sp = (Math.pow(snow.sz * .8, 2) * .15) * sp;
        snow.sp = snow.sp < min ? min : snow.sp;
        arr.push(snow);
    }
    go();

    function go() {
        window.requestAnimationFrame(go);
        
        // Clear canvas and draw background first
        ctx2d.clearRect(0, 0, w, h);
        drawBackground();
        
        // Then draw snow on top
        for (var i = 0; i < arr.length; ++i) {
            f = arr[i];
            f.t += .05;
            f.t = f.t >= Math.PI * 2 ? 0 : f.t;
            f.y += f.sp;
            f.x += Math.sin(f.t * tsc) * (f.sz * .3);
            if (f.y > h + 50) f.y = -10 - Math.random() * mv;
            if (f.x > w + mv) f.x = -mv;
            if (f.x < -mv) f.x = w + mv;
            f.draw();
        }
    }

    function Flake() {
        this.draw = function() {
            this.g = ctx2d.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.sz);
            this.g.addColorStop(0, 'hsla(255,255%,255%,1)');
            this.g.addColorStop(1, 'hsla(255,255%,255%,0)');
            ctx2d.moveTo(this.x, this.y);
            ctx2d.fillStyle = this.g;
            ctx2d.beginPath();
            ctx2d.arc(this.x, this.y, this.sz, 0, Math.PI * 2, true);
            ctx2d.fill();
        }
    }
}

/*________________________________________*/
window.addEventListener('resize', function() {
    c.width = w = window.innerWidth;
    c.height = h = window.innerHeight;
    // Redraw background on resize
    if (imageLoaded) {
        drawBackground();
    }
}, false);
