    </main>
    
    <!-- Footer -->
    <footer class="relative z-20 bg-black pt-16 pb-8 flex flex-col items-center border-t border-white/5 w-full overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 w-full flex flex-col items-center z-10">
            
            <!-- Social Icons Top Row -->
            <div class="flex gap-4 mb-3">
                <a href="https://www.facebook.com/profile?id=61557997221026" target="_blank" class="text-white hover:text-gray-300 transition-colors text-sm">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/cyberx.ye/" target="_blank" class="text-white hover:text-gray-300 transition-colors text-sm">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" target="_blank" class="text-white hover:text-gray-300 transition-colors text-sm">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>

            <!-- Minimalist Copyright Info -->
            <div class="text-center font-['Inter'] text-[11px] leading-relaxed mx-auto uppercase mb-12 flex flex-col items-center gap-0.5 tracking-wider" style="color:#aaaaaa;">
                <div><?php echo t('footer.privacy'); ?></div>
                <div><?php echo t('footer.copyright', ['year' => date('Y')]); ?></div>
                <div><?php echo t('footer.rights'); ?></div>
            </div>
            
        </div>

        <!-- Stacked Typography CYBERX -->
        <div class="select-none pointer-events-none mt-4 bleed-text" style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:900; line-height:0.85; letter-spacing:-0.01em;">
            <!-- Top White Layer -->
            <div style="color:#ffffff; width:100%; text-align:center; white-space:nowrap;">
                CYBERX
            </div>
            <!-- Middle Gray Layer -->
            <div style="color:#777777; width:100%; text-align:center; white-space:nowrap; margin-top:-0.05em;">
                CYBERX
            </div>
            <!-- Bottom Dark Gray Layer -->
            <div style="color:#1c1c1c; width:100%; text-align:center; white-space:nowrap; margin-top:-0.05em;">
                CYBERX
            </div>
        </div>
    </footer>
    
    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/967733388080?text=Hello%2C%20I%27m%20interested%20in%20your%20services!" 
       target="_blank" 
       rel="noopener noreferrer"
       class="whatsapp-float bg-white/95 text-black hover:bg-white z-[9999]"
       aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <!-- Tooltip -->
        <span class="whatsapp-tooltip text-black bg-white/90">
            <?php echo t('footer.chat_tooltip'); ?>
        </span>
    </a>

    <style>
        .bleed-text {
            font-size: 25vw;
            width: 110%;
            margin-left: -5%;
        }

        /* Floating WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            backdrop-filter: blur(4px);
        }

        .whatsapp-float:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 8px 30px rgba(255, 255, 255, 0.25);
        }

        .whatsapp-float i {
            transition: transform 0.3s ease;
        }

        .whatsapp-float:hover i {
            transform: rotate(15deg) scale(1.1);
        }

        /* Tooltip */
        .whatsapp-tooltip {
            position: absolute;
            right: 75px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transform: translateX(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .whatsapp-tooltip::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 50%;
            transform: translateY(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent transparent rgba(255, 255, 255, 0.9);
        }

        .whatsapp-float:hover .whatsapp-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .whatsapp-float {
                bottom: 20px;
                right: 20px;
                width: 55px;
                height: 55px;
                font-size: 28px;
            }
            
            .whatsapp-tooltip {
                display: none;
            }
        }
    </style>

    <!-- Language Toggle URL Preservation Script -->
    <script>
    (function() {
        // Make language toggle links preserve current page URL
        document.querySelectorAll('.lang-toggle a, .lang-toggle-mobile a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var lang = this.href.split('lang=')[1];
                var currentUrl = window.location.pathname + window.location.search;
                // Remove existing lang param
                currentUrl = currentUrl.replace(/([?&])lang=[^&]*/g, '');
                currentUrl = currentUrl.replace(/[?&]$/, '');
                var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
                window.location.href = currentUrl + separator + 'lang=' + lang;
            });
        });
    })();
    </script>

    <!-- JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
