(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSleighAnimation);
    } else {
        initSleighAnimation();
    }
    
    function initSleighAnimation() {
        var sleigh = document.querySelector('.sleigh-santa');
        
        if (!sleigh) {
            console.warn('Santa sleigh element not found');
            return;
        }
        
        // Get sleigh dimensions
        var sleighWidth = 295; // Width from CSS
        var sleighHeight = 155; // Height from CSS
        
        // Configuration
        var config = {
            speed: 1.5, // Current speed (will be randomized)
            minSpeed: 0.8, // Minimum speed
            maxSpeed: 2.0, // Maximum speed
            maxAngleChange: 10 * (Math.PI / 180), // Maximum 10 degrees per frame in radians
            maxHorizontalDeviation: 10 * (Math.PI / 180), // Maximum 10 degrees from horizontal axis
            topPosition: -16, // Keep original top position
            animationId: null,
            isPaused: false
        };
        
        // Function to randomize speed
        function randomizeSpeed() {
            config.speed = config.minSpeed + Math.random() * (config.maxSpeed - config.minSpeed);
        }
        
        // State
        var state = {
            x: 0, // Current X position
            y: config.topPosition, // Current Y position
            targetX: 0, // Target X position
            targetY: config.topPosition, // Target Y position
            velocityX: 0, // Current velocity X component
            velocityY: 0, // Current velocity Y component
            currentAngle: 0, // Current movement angle in radians
            direction: 1 // 1 for left-to-right, -1 for right-to-left
        };
        
        // Function to generate random Y position (widely varied)
        function getRandomStartY() {
            // Use a very wide range - from near top to near bottom of visible area
            var minY = 50; // Allow movement well above the base position
            var maxY = Math.min(window.innerHeight - 50, config.topPosition + window.innerHeight * 0.8);
            // Ensure we have a good range
            if (maxY - minY < 300) {
                maxY = minY + 500; // Minimum range of 500px
            }
            return minY + Math.random() * (maxY - minY);
        }
        
        // Initialize sleigh position
        function initSleigh() {
            // Randomly choose direction: left-to-right or right-to-left
            state.direction = Math.random() < 0.5 ? 1 : -1;
            
            if (state.direction === 1) {
                // Moving left to right - start at left end (completely off-screen)
                state.x = -sleighWidth;
                state.currentAngle = 0; // Moving right (0 radians)
                sleigh.style.transform = 'scaleX(1)'; // Normal orientation
            } else {
                // Moving right to left - start at right end (completely off-screen)
                state.x = window.innerWidth;
                state.currentAngle = Math.PI; // Moving left (π radians)
                sleigh.style.transform = 'scaleX(-1)'; // Flipped horizontally
            }
            
            // Random Y position - widely varied each time
            state.y = getRandomStartY();
            
            // Randomize speed
            randomizeSpeed();
            
            // Set initial target
            generateNewTarget();
            
            sleigh.style.position = 'absolute';
            sleigh.style.top = state.y + 'px';
            sleigh.style.left = state.x + 'px';
            sleigh.style.zIndex = '9999'; // Ensure it's above other elements
        }
        
        // Generate a new random target position
        function generateNewTarget() {
            if (state.direction === 1) {
                // Moving left to right - must move horizontally while LEFT end is visible
                // Left end position = state.x
                var isLeftEndVisible = state.x >= 0 && state.x < window.innerWidth;
                
                if (isLeftEndVisible) {
                    // While left end is visible, move strictly horizontally - targets should be to the right
                    state.targetX = state.x + 100 + Math.random() * 300; // Always move significantly right
                    
                    // Very minimal vertical variation - keep it horizontal
                    var verticalVariation = 20; // Very small vertical range for horizontal movement
                    state.targetY = state.y + (Math.random() - 0.5) * verticalVariation;
                } else {
                    // If left end is not visible, can have more variation
                    state.targetX = state.x + 50 + Math.random() * 200;
                    var maxVerticalRange = window.innerHeight * 0.7;
                    var minY = Math.max(-100, config.topPosition - 100);
                    var maxY = Math.min(window.innerHeight - 100, config.topPosition + maxVerticalRange);
                    state.targetY = minY + Math.random() * (maxY - minY);
                }
                
                // Ensure target is ahead of current position (moving right)
                if (state.targetX <= state.x) {
                    state.targetX = state.x + 50 + Math.random() * 150;
                }
            } else {
                // Moving right to left - must move horizontally while RIGHT end is visible
                // Right end position = state.x + sleighWidth
                var rightEndX = state.x + sleighWidth;
                var isRightEndVisible = rightEndX > 0 && rightEndX <= window.innerWidth;
                
                if (isRightEndVisible) {
                    // While right end is visible, move strictly horizontally - targets should be to the left
                    state.targetX = state.x - 100 - Math.random() * 300; // Always move significantly left
                    
                    // Very minimal vertical variation - keep it horizontal
                    var verticalVariation = 20; // Very small vertical range for horizontal movement
                    state.targetY = state.y + (Math.random() - 0.5) * verticalVariation;
                } else {
                    // If right end is not visible, can have more variation
                    state.targetX = state.x - 50 - Math.random() * 200;
                    var maxVerticalRange = window.innerHeight * 0.7;
                    var minY = Math.max(-100, config.topPosition - 100);
                    var maxY = Math.min(window.innerHeight - 100, config.topPosition + maxVerticalRange);
                    state.targetY = minY + Math.random() * (maxY - minY);
                }
                
                // Ensure target is ahead of current position (moving left)
                if (state.targetX >= state.x) {
                    state.targetX = state.x - 50 - Math.random() * 150;
                }
            }
            
            // Clamp to screen bounds
            if (state.direction === 1) {
                state.targetX = Math.max(0, Math.min(window.innerWidth + 100, state.targetX));
            } else {
                state.targetX = Math.max(-sleighWidth - 100, Math.min(window.innerWidth, state.targetX));
            }
            
            var maxVerticalRange = window.innerHeight * 0.7;
            var minY = Math.max(-100, config.topPosition - 100);
            var maxY = Math.min(window.innerHeight - 100, config.topPosition + maxVerticalRange);
            state.targetY = Math.max(minY, Math.min(maxY, state.targetY));
        }
        
        // Calculate angle between two points
        function calculateAngle(x1, y1, x2, y2) {
            return Math.atan2(y2 - y1, x2 - x1);
        }
        
        // Normalize angle to -PI to PI range
        function normalizeAngle(angle) {
            while (angle > Math.PI) angle -= 2 * Math.PI;
            while (angle < -Math.PI) angle += 2 * Math.PI;
            return angle;
        }
        
        // Calculate angle difference
        function angleDifference(angle1, angle2) {
            var diff = normalizeAngle(angle1 - angle2);
            return diff;
        }
        
        // Clamp angle to within maxHorizontalDeviation from horizontal axis based on direction
        function clampToHorizontal(angle, direction) {
            var horizontalAngle = direction === 1 ? 0 : Math.PI; // 0 for left-to-right, π for right-to-left
            var angleRelativeToHorizontal = normalizeAngle(angle - horizontalAngle);
            
            // Clamp to ±maxHorizontalDeviation
            if (angleRelativeToHorizontal > config.maxHorizontalDeviation) {
                angleRelativeToHorizontal = config.maxHorizontalDeviation;
            } else if (angleRelativeToHorizontal < -config.maxHorizontalDeviation) {
                angleRelativeToHorizontal = -config.maxHorizontalDeviation;
            }
            
            // Return the clamped angle in absolute coordinates
            return normalizeAngle(horizontalAngle + angleRelativeToHorizontal);
        }
        
        // Animation function
        function animateSleigh() {
            if (config.isPaused) {
                return;
            }
            
            // Calculate direction to target
            var dx = state.targetX - state.x;
            var dy = state.targetY - state.y;
            var distance = Math.sqrt(dx * dx + dy * dy);
            
            // If reached target (or very close), generate new target (keep same speed)
            if (distance < 5) {
                generateNewTarget();
                dx = state.targetX - state.x;
                dy = state.targetY - state.y;
                distance = Math.sqrt(dx * dx + dy * dy);
            }
            
            // Calculate desired angle to target
            var desiredAngle = calculateAngle(state.x, state.y, state.targetX, state.targetY);
            
            // Calculate angle difference from current direction
            var angleDiff = angleDifference(desiredAngle, state.currentAngle);
            
            // Limit angle change to max 20 degrees per frame
            if (Math.abs(angleDiff) > config.maxAngleChange) {
                angleDiff = angleDiff > 0 ? config.maxAngleChange : -config.maxAngleChange;
            }
            
            // Update current angle
            state.currentAngle = normalizeAngle(state.currentAngle + angleDiff);
            
            // Clamp angle to within 20 degrees of horizontal axis
            state.currentAngle = clampToHorizontal(state.currentAngle, state.direction);
            
            // Calculate velocity components based on current angle
            state.velocityX = Math.cos(state.currentAngle) * config.speed;
            state.velocityY = Math.sin(state.currentAngle) * config.speed;
            
            // Update position
            state.x += state.velocityX;
            state.y += state.velocityY;
            
            // Check if top edge approaches top of browser - reflect downwards if moving upward
            var topEdgeThreshold = 5; // Small threshold to detect approaching edge
            if (state.y <= topEdgeThreshold && state.velocityY < 0) {
                // Top edge approaching top of browser and moving upward - reflect downwards
                // Clamp Y to prevent going off-screen
                if (state.y < 0) {
                    state.y = 0;
                }
                
                // Reverse vertical component by flipping the angle's vertical component
                // Keep horizontal direction but flip vertical component
                var horizontalAngle = state.direction === 1 ? 0 : Math.PI;
                var angleRelativeToHorizontal = normalizeAngle(state.currentAngle - horizontalAngle);
                
                // Flip vertical component (if negative/up, make positive/down, and vice versa)
                angleRelativeToHorizontal = -angleRelativeToHorizontal;
                
                // Reconstruct angle with flipped vertical component
                state.currentAngle = normalizeAngle(horizontalAngle + angleRelativeToHorizontal);
                
                // Ensure angle is still within bounds
                state.currentAngle = clampToHorizontal(state.currentAngle, state.direction);
                
                // Recalculate velocity with new angle
                state.velocityX = Math.cos(state.currentAngle) * config.speed;
                state.velocityY = Math.sin(state.currentAngle) * config.speed;
                
                // Generate new target to adjust path
                generateNewTarget();
            }
            
            // Check if bottom edge approaches bottom of browser - reflect upwards if moving downward
            var bottomEdgeThreshold = 5; // Small threshold to detect approaching edge
            var bottomEdgeY = state.y + sleighHeight;
            if (bottomEdgeY >= window.innerHeight - bottomEdgeThreshold && state.velocityY > 0) {
                // Bottom edge approaching bottom of browser and moving downward - reflect upwards
                // Clamp Y to prevent going off-screen
                if (bottomEdgeY > window.innerHeight) {
                    state.y = window.innerHeight - sleighHeight;
                }
                
                // Reverse vertical component by flipping the angle's vertical component
                // Keep horizontal direction but flip vertical component
                var horizontalAngle = state.direction === 1 ? 0 : Math.PI;
                var angleRelativeToHorizontal = normalizeAngle(state.currentAngle - horizontalAngle);
                
                // Flip vertical component (if positive/down, make negative/up, and vice versa)
                angleRelativeToHorizontal = -angleRelativeToHorizontal;
                
                // Reconstruct angle with flipped vertical component
                state.currentAngle = normalizeAngle(horizontalAngle + angleRelativeToHorizontal);
                
                // Ensure angle is still within bounds
                state.currentAngle = clampToHorizontal(state.currentAngle, state.direction);
                
                // Recalculate velocity with new angle
                state.velocityX = Math.cos(state.currentAngle) * config.speed;
                state.velocityY = Math.sin(state.currentAngle) * config.speed;
                
                // Generate new target to adjust path
                generateNewTarget();
            }
            
            // Check if sleigh has moved completely off-screen
            if (state.direction === 1) {
                // Moving left to right - check if entire sleigh (right edge) is off-screen to the right
                if (state.x >= window.innerWidth) {
                    // Reset with random direction for new journey
                    state.direction = Math.random() < 0.5 ? 1 : -1;
                    
                    if (state.direction === 1) {
                        // New journey: left to right
                        state.x = -sleighWidth;
                        state.currentAngle = 0; // Moving right
                        sleigh.style.transform = 'scaleX(1)'; // Normal orientation
                    } else {
                        // New journey: right to left
                        state.x = window.innerWidth;
                        state.currentAngle = Math.PI; // Moving left
                        sleigh.style.transform = 'scaleX(-1)'; // Flipped horizontally
                    }
                    
                    // Get a new widely random Y position
                    state.y = getRandomStartY();
                    // Randomize speed for the new journey
                    randomizeSpeed();
                    generateNewTarget();
                }
            } else {
                // Moving right to left - check if right end has moved completely off-screen to the left
                // Right end position = state.x + sleighWidth
                // Reset when right end (state.x + sleighWidth) <= 0, meaning entire sleigh is off-screen left
                if (state.x + sleighWidth <= 0) {
                    // Reset with random direction for new journey
                    state.direction = Math.random() < 0.5 ? 1 : -1;
                    
                    if (state.direction === 1) {
                        // New journey: left to right
                        state.x = -sleighWidth;
                        state.currentAngle = 0; // Moving right
                        sleigh.style.transform = 'scaleX(1)'; // Normal orientation
                    } else {
                        // New journey: right to left
                        state.x = window.innerWidth;
                        state.currentAngle = Math.PI; // Moving left
                        sleigh.style.transform = 'scaleX(-1)'; // Flipped horizontally
                    }
                    
                    // Get a new widely random Y position
                    state.y = getRandomStartY();
                    // Randomize speed for the new journey
                    randomizeSpeed();
                    generateNewTarget();
                }
            }
            
            // Keep sleigh within vertical bounds (with increased range)
            var margin = 50;
            var maxVerticalRange = window.innerHeight * 0.7;
            var minY = Math.max(-100, config.topPosition - 100);
            var maxY = Math.min(window.innerHeight - 100, config.topPosition + maxVerticalRange);
            
            if (state.y < minY - margin) {
                state.y = minY;
            } else if (state.y > maxY + margin) {
                state.y = maxY;
            }
            
            // Ensure whole sleigh stays visible during journey
            if (state.direction === 1) {
                // Moving left to right: keep left end visible (state.x >= 0) while on screen
                // Don't clamp during journey - let it move naturally until left end goes off-screen
            } else {
                // Moving right to left: keep right end visible (state.x + sleighWidth <= window.innerWidth) while on screen
                // Don't clamp during journey - let it move naturally until right end goes off-screen
            }
            
            // Update sleigh position
            sleigh.style.left = state.x + 'px';
            sleigh.style.top = state.y + 'px';
            
            // Apply transform based on direction
            if (state.direction === 1) {
                sleigh.style.transform = 'scaleX(1)'; // Normal orientation (left to right)
            } else {
                sleigh.style.transform = 'scaleX(-1)'; // Flipped horizontally (right to left)
            }
            
            // Continue animation
            config.animationId = requestAnimationFrame(animateSleigh);
        }
        
        // Start animation
        function startAnimation() {
            if (config.animationId) {
                cancelAnimationFrame(config.animationId);
            }
            initSleigh();
            config.isPaused = false;
            animateSleigh();
        }
        
        // Pause animation
        function pauseAnimation() {
            config.isPaused = true;
            if (config.animationId) {
                cancelAnimationFrame(config.animationId);
            }
        }
        
        // Resume animation
        function resumeAnimation() {
            if (config.isPaused) {
                config.isPaused = false;
                animateSleigh();
            }
        }
        
        // Handle window resize
        function handleResize() {
            // Clamp current position to new screen bounds
            state.x = Math.max(0, Math.min(window.innerWidth - sleighWidth, state.x));
            state.targetX = Math.max(0, Math.min(window.innerWidth - sleighWidth, state.targetX));
        }
        
        // Expose controls to window (optional - for debugging or external control)
        window.sleighControls = {
            start: startAnimation,
            pause: pauseAnimation,
            resume: resumeAnimation,
            setSpeed: function(speed) {
                config.speed = Math.max(0.1, Math.min(10, speed)); // Clamp between 0.1 and 10
            },
            getSpeed: function() {
                return config.speed;
            },
            generateNewTarget: generateNewTarget
        };
        
        // Initialize and start
        initSleigh();
        startAnimation();
        
        // Handle window resize
        window.addEventListener('resize', handleResize);
        
        // Optional: Pause when page is not visible (saves resources)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                pauseAnimation();
            } else {
                resumeAnimation();
            }
        });
    }
})();
