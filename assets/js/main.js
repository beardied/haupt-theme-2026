/**
 * Haupt Recruitment 2026 - Main JavaScript
 * Handles animations, interactions, and functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ==========================================
    // INITIALIZE AOS (Animate On Scroll)
    // ==========================================
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
            delay: 0,
            disable: function() {
                // Disable on mobile for better performance
                return window.innerWidth < 768 && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            }
        });
    }

    // ==========================================
    // HEADER SCROLL EFFECT
    // ==========================================
    const header = document.getElementById('site-header');
    const scrollProgress = document.getElementById('scroll-progress');
    
    function handleScroll() {
        const scrollY = window.scrollY;
        
        // Header background
        if (scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Scroll progress bar
        if (scrollProgress) {
            const windowHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = (scrollY / windowHeight) * 100;
            scrollProgress.style.width = progress + '%';
        }
        
        // Scroll to top button
        const scrollTop = document.getElementById('scroll-top');
        if (scrollTop) {
            if (scrollY > 500) {
                scrollTop.classList.add('visible');
            } else {
                scrollTop.classList.remove('visible');
            }
        }
    }
    
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    // ==========================================
    // MOBILE MENU
    // ==========================================
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuClose = document.querySelector('.mobile-menu-close');
    
    if (menuToggle && mobileMenu) {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'mobile-menu-overlay';
        document.body.appendChild(overlay);
        
        function openMenu() {
            mobileMenu.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            menuToggle.setAttribute('aria-expanded', 'true');
        }
        
        function closeMenu() {
            mobileMenu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            menuToggle.setAttribute('aria-expanded', 'false');
        }
        
        menuToggle.addEventListener('click', openMenu);
        menuClose.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMenu();
            }
        });
        
        // Close menu when clicking on links
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
    }

    // ==========================================
    // SCROLL TO TOP
    // ==========================================
    const scrollTopBtn = document.getElementById('scroll-top');
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ==========================================
    // ANIMATED COUNTERS
    // ==========================================
    const counters = document.querySelectorAll('.counter-number[data-target], .hero-stat-number[data-target]');
    
    function animateCounter(counter) {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const start = 0;
        const startTime = performance.now();
        
        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (ease-out-quart)
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const current = Math.floor(easeOutQuart * (target - start) + start);
            
            counter.textContent = current.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString();
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
    
    // Intersection Observer for counters
    if (counters.length > 0 && 'IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    entry.target.classList.add('counted');
                    animateCounter(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(function(counter) {
            counterObserver.observe(counter);
        });
    }

    // ==========================================
    // FAQ ACCORDION
    // ==========================================
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question');
        
        if (question) {
            question.addEventListener('click', function() {
                const isActive = item.classList.contains('active');
                
                // Close all other items
                faqItems.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const otherQuestion = otherItem.querySelector('.faq-question');
                        if (otherQuestion) {
                            otherQuestion.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active');
                question.setAttribute('aria-expanded', !isActive);
            });
        }
    });
    
    // ==========================================
    // GUTENBERG FAQ AUTO-CONVERSION
    // Converts H2 "FAQ" + H3 questions + P answers into styled accordion
    // ==========================================
    function initGutenbergFAQ() {
        const contentAreas = document.querySelectorAll('.expert-article .entry-content, .expert-article .content, .article-single .content, .article-single .entry-content, .entry-content, .content');
        
        contentAreas.forEach(function(content) {
            const headings = Array.from(content.querySelectorAll('h2'));
            
            headings.forEach(function(heading) {
                if (!heading.textContent.toLowerCase().includes('faq')) return;
                
                const faqSection = heading;
                const faqItems = [];
                const elementsToRemove = [];
                
                // First pass: collect all H3 + following P elements
                let sibling = faqSection.nextElementSibling;
                let currentItem = null;
                
                while (sibling && sibling.tagName !== 'H2') {
                    const next = sibling.nextElementSibling;
                    
                    if (sibling.tagName === 'H3') {
                        // Save previous item if exists
                        if (currentItem) {
                            faqItems.push(currentItem);
                        }
                        currentItem = {
                            question: sibling.textContent.trim(),
                            answerHTML: '',
                            elements: [sibling]
                        };
                        elementsToRemove.push(sibling);
                    } 
                    else if (sibling.tagName === 'P' && currentItem) {
                        currentItem.answerHTML += sibling.outerHTML;
                        currentItem.elements.push(sibling);
                        elementsToRemove.push(sibling);
                    }
                    else if (currentItem) {
                        // Non-P element ends current answer
                        faqItems.push(currentItem);
                        currentItem = null;
                    }
                    
                    sibling = next;
                }
                
                // Don't forget the last item
                if (currentItem) {
                    faqItems.push(currentItem);
                }
                
                if (faqItems.length === 0) return;
                
                // Create styled FAQ container
                const faqContainer = document.createElement('div');
                faqContainer.className = 'expert-faq-section';
                
                // Add heading
                const faqHeading = document.createElement('h2');
                faqHeading.textContent = faqSection.textContent;
                faqContainer.appendChild(faqHeading);
                
                // Add FAQ items
                const faqList = document.createElement('div');
                faqList.className = 'faq-list';
                
                faqItems.forEach(function(item) {
                    const faqItem = document.createElement('div');
                    faqItem.className = 'faq-item';
                    
                    const questionBtn = document.createElement('button');
                    questionBtn.className = 'faq-question';
                    questionBtn.setAttribute('aria-expanded', 'false');
                    questionBtn.innerHTML = escapeHtml(item.question) + 
                        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                    
                    const answerDiv = document.createElement('div');
                    answerDiv.className = 'faq-answer';
                    answerDiv.innerHTML = item.answerHTML;
                    
                    faqItem.appendChild(questionBtn);
                    faqItem.appendChild(answerDiv);
                    faqList.appendChild(faqItem);
                    
                    // Add click handler
                    questionBtn.addEventListener('click', function() {
                        const isActive = faqItem.classList.contains('active');
                        
                        // Close all others
                        faqContainer.querySelectorAll('.faq-item').forEach(function(other) {
                            other.classList.remove('active');
                            other.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                        });
                        
                        // Toggle this one
                        faqItem.classList.toggle('active', !isActive);
                        questionBtn.setAttribute('aria-expanded', !isActive);
                    });
                });
                
                faqContainer.appendChild(faqList);
                
                // Insert new container and remove old elements
                faqSection.parentNode.insertBefore(faqContainer, faqSection);
                faqSection.remove();
                elementsToRemove.forEach(function(el) {
                    if (el.parentNode) el.parentNode.removeChild(el);
                });
            });
        });
    }
    
    // Helper: Escape HTML entities
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize FAQ conversion
    initGutenbergFAQ();

    // ==========================================
    // PARALLAX EFFECTS
    // ==========================================
    const parallaxElements = document.querySelectorAll('.parallax');
    
    function handleParallax() {
        const scrollY = window.scrollY;
        
        parallaxElements.forEach(function(element) {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrollY * speed);
            element.style.transform = 'translateY(' + yPos + 'px)';
        });
    }
    
    if (parallaxElements.length > 0) {
        window.addEventListener('scroll', handleParallax, { passive: true });
    }

    // ==========================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                const headerHeight = header.offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ==========================================
    // LAZY LOADING IMAGES (Fallback)
    // ==========================================
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            img.src = img.dataset.src;
            img.loading = 'lazy';
        });
    } else {
        // Fallback for older browsers
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    // ==========================================
    // FORM VALIDATION
    // ==========================================
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Add error message if not exists
                    let errorMsg = field.parentElement.querySelector('.form-error');
                    if (!errorMsg) {
                        errorMsg = document.createElement('span');
                        errorMsg.className = 'form-error';
                        errorMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> This field is required';
                        field.parentElement.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.parentElement.querySelector('.form-error');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    // ==========================================
    // JOB FILTER AJAX
    // ==========================================
    const jobFilterForm = document.getElementById('job-filter-form');
    
    if (jobFilterForm && typeof hauptData !== 'undefined') {
        const jobResults = document.getElementById('job-results');
        const jobLoading = document.getElementById('job-loading');
        
        function filterJobs() {
            const formData = new FormData(jobFilterForm);
            const params = new URLSearchParams(formData);
            
            // Show loading
            if (jobLoading) jobLoading.style.display = 'block';
            if (jobResults) jobResults.style.opacity = '0.5';
            
            fetch(hauptData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=haupt_filter_jobs&nonce=' + hauptData.nonce + '&' + params.toString()
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success && jobResults) {
                    updateJobResults(data.data.jobs);
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
            })
            .finally(function() {
                if (jobLoading) jobLoading.style.display = 'none';
                if (jobResults) jobResults.style.opacity = '1';
            });
        }
        
        function updateJobResults(jobs) {
            if (jobs.length === 0) {
                jobResults.innerHTML = '<div class="no-results"><p>No jobs found matching your criteria.</p></div>';
                return;
            }
            
            let html = '<div class="grid grid-auto">';
            jobs.forEach(function(job) {
                html += `
                    <article class="card">
                        <div class="card-image">
                            <img src="${job.thumbnail || '/wp-content/themes/hauptrecruitment-2026/assets/images/job-placeholder.jpg'}" alt="${job.title}" loading="lazy">
                        </div>
                        <div class="card-content">
                            <div class="card-meta">
                                ${job.location ? `<span class="card-meta-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> ${job.location}</span>` : ''}
                                ${job.type ? `<span class="card-meta-item">${job.type}</span>` : ''}
                            </div>
                            <h3 class="card-title"><a href="${job.permalink}">${job.title}</a></h3>
                            <p class="card-text">${job.excerpt}</p>
                            <div class="card-footer">
                                ${job.salary ? `<span class="card-salary">${job.salary}</span>` : ''}
                                <a href="${job.permalink}" class="btn btn-sm btn-primary">View Job</a>
                            </div>
                        </div>
                    </article>
                `;
            });
            html += '</div>';
            
            jobResults.innerHTML = html;
            
            // Re-initialize AOS for new elements
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }
        
        // Filter on change
        jobFilterForm.querySelectorAll('select, input').forEach(function(input) {
            input.addEventListener('change', filterJobs);
        });
    }

    // ==========================================
    // MAGNETIC BUTTON EFFECT
    // ==========================================
    const magneticButtons = document.querySelectorAll('.btn-magnetic');
    
    if (!window.matchMedia('(pointer: coarse)').matches) {
        magneticButtons.forEach(function(button) {
            button.addEventListener('mousemove', function(e) {
                const rect = button.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                button.style.transform = 'translate(' + (x * 0.2) + 'px, ' + (y * 0.2) + 'px)';
            });
            
            button.addEventListener('mouseleave', function() {
                button.style.transform = '';
            });
        });
    }

    // ==========================================
    // TABS
    // ==========================================
    const tabGroups = document.querySelectorAll('[data-tabs]');
    
    tabGroups.forEach(function(group) {
        const tabs = group.querySelectorAll('[data-tab]');
        const panels = group.querySelectorAll('[data-tab-panel]');
        
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const target = this.dataset.tab;
                
                // Update tabs
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                
                // Update panels
                panels.forEach(function(panel) {
                    if (panel.dataset.tabPanel === target) {
                        panel.classList.add('active');
                        panel.hidden = false;
                    } else {
                        panel.classList.remove('active');
                        panel.hidden = true;
                    }
                });
            });
        });
    });

    // ==========================================
    // PRELOADER (if present)
    // ==========================================
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', function() {
            preloader.classList.add('hidden');
            setTimeout(function() {
                preloader.remove();
            }, 500);
        });
    }

    // ==========================================
    // REVEAL ANIMATIONS ON SCROLL
    // ==========================================
    const revealElements = document.querySelectorAll('.reveal');
    
    if (revealElements.length > 0 && 'IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        
        revealElements.forEach(function(el) {
            revealObserver.observe(el);
        });
    }

    // ==========================================
    // REDUCED MOTION PREFERENCE
    // ==========================================
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        // Disable AOS animations
        if (typeof AOS !== 'undefined') {
            AOS.init({ disable: true });
        }
        
        // Disable custom animations
        document.querySelectorAll('.parallax').forEach(function(el) {
            el.style.transform = 'none';
        });
    }

    console.log('Haupt Recruitment 2026 theme loaded successfully');
});
