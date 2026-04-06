/**
 * Haupt Recruitment Forms JavaScript
 * Multi-step form handling, signature pad, and validation
 */

(function() {
    'use strict';
    
    // Form state
    let currentStep = 1;
    let totalSteps = 1;
    let signaturePad = null;
    
    /**
     * Initialize on DOM ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        initMultiStepForm();
        initSignaturePad();
        initFileUploads();
        initValidation();
        initSingleStepForm();
    });
    
    /**
     * Initialize single-step form (candidate registration, employer contact, etc.)
     */
    function initSingleStepForm() {
        const forms = document.querySelectorAll('.registration-form, .contact-form, .optout-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Add signature data if exists
                if (signaturePad && !signaturePad.isEmpty()) {
                    const signatureInput = form.querySelector('input[name="signature_data"]');
                    if (signatureInput) {
                        signatureInput.value = signaturePad.toDataURL('image/png');
                    }
                }
            });
        });
    }
    
    /**
     * Multi-step form navigation
     */
    function initMultiStepForm() {
        const form = document.querySelector('.haupt-multi-step-form');
        if (!form) return;
        
        const steps = form.querySelectorAll('.haupt-form-step');
        totalSteps = steps.length;
        
        if (totalSteps <= 1) return;
        
        // Initialize progress bar
        updateProgressBar();
        
        // Next buttons
        form.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (validateStep(currentStep)) {
                    goToStep(currentStep + 1);
                }
            });
        });
        
        // Previous buttons
        form.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                goToStep(currentStep - 1);
            });
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return false;
            }
            
            // Add signature data if exists
            if (signaturePad && !signaturePad.isEmpty()) {
                const signatureInput = form.querySelector('input[name="signature_data"]');
                if (signatureInput) {
                    signatureInput.value = signaturePad.toDataURL('image/png');
                }
            }
        });
    }
    
    /**
     * Navigate to a specific step
     */
    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        
        const form = document.querySelector('.haupt-multi-step-form');
        const steps = form.querySelectorAll('.haupt-form-step');
        
        // Hide current step
        steps[currentStep - 1].classList.remove('active');
        
        // Show new step
        currentStep = step;
        steps[currentStep - 1].classList.add('active');
        
        // Update progress bar
        updateProgressBar();
        
        // Scroll to top of form
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    /**
     * Update progress bar
     */
    function updateProgressBar() {
        const progressSteps = document.querySelectorAll('.haupt-form-progress .step');
        if (!progressSteps.length) return;
        
        progressSteps.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            
            if (index + 1 < currentStep) {
                step.classList.add('completed');
            } else if (index + 1 === currentStep) {
                step.classList.add('active');
            }
        });
    }
    
    /**
     * Validate current step
     */
    function validateStep(step) {
        const form = document.querySelector('.haupt-multi-step-form');
        const stepElement = form.querySelector('.haupt-form-step[data-step="' + step + '"]');
        if (!stepElement) return true;
        
        let isValid = true;
        
        // Check required fields
        const requiredFields = stepElement.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            // Clear previous errors
            field.classList.remove('error');
            const errorEl = field.parentNode.querySelector('.field-error');
            if (errorEl) errorEl.remove();
            
            // Check if valid
            if (!field.checkValidity()) {
                isValid = false;
                field.classList.add('error');
                
                // Add error message
                const error = document.createElement('span');
                error.className = 'field-error';
                error.textContent = field.validationMessage || 'This field is required';
                field.parentNode.appendChild(error);
            }
        });
        
        // Validate signature if present on this step
        const signatureContainer = stepElement.querySelector('.signature-pad-container');
        if (signatureContainer) {
            const signatureInput = form.querySelector('input[name="signature_data"]');
            if (signatureInput && signatureInput.hasAttribute('required')) {
                if (signaturePad && signaturePad.isEmpty()) {
                    isValid = false;
                    
                    // Show signature error
                    let errorEl = signatureContainer.querySelector('.field-error');
                    if (!errorEl) {
                        errorEl = document.createElement('span');
                        errorEl.className = 'field-error';
                        errorEl.textContent = 'Please sign above';
                        signatureContainer.appendChild(errorEl);
                    }
                }
            }
        }
        
        return isValid;
    }
    
    /**
     * Initialize signature pad
     */
    function initSignaturePad() {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;
        
        // Handle canvas sizing
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
        }
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        // Initialize SignaturePad
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 45, 114)',
            minWidth: 1,
            maxWidth: 3,
            throttle: 16
        });
        
        // Clear button
        const clearBtn = document.getElementById('clear-signature');
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                signaturePad.clear();
                
                // Remove any error messages
                const container = canvas.closest('.signature-pad-container');
                const errorEl = container.querySelector('.field-error');
                if (errorEl) errorEl.remove();
            });
        }
    }
    
    /**
     * Initialize file upload preview
     */
    function initFileUploads() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const wrapper = this.closest('.file-upload-wrapper');
                const label = wrapper.querySelector('.file-upload-label');
                const textSpan = label.querySelector('.text strong');
                
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    textSpan.textContent = file.name + ' (' + fileSize + ' MB)';
                    label.style.borderColor = '#00a5b5';
                    label.style.background = '#e8f4f5';
                } else {
                    textSpan.textContent = 'Choose a file or drag it here';
                    label.style.borderColor = '#ccc';
                    label.style.background = '#fafafa';
                }
            });
            
            // Drag and drop support
            const wrapper = input.closest('.file-upload-wrapper');
            const label = wrapper.querySelector('.file-upload-label');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                label.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                label.addEventListener(eventName, function() {
                    label.style.borderColor = '#00a5b5';
                    label.style.background = '#e8f4f5';
                }, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                label.addEventListener(eventName, function() {
                    if (!input.files.length) {
                        label.style.borderColor = '#ccc';
                        label.style.background = '#fafafa';
                    }
                }, false);
            });
            
            label.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                input.dispatchEvent(new Event('change'));
            }, false);
        });
    }
    
    /**
     * Initialize real-time validation
     */
    function initValidation() {
        const form = document.querySelector('.haupt-multi-step-form');
        if (!form) return;
        
        // Remove error on input
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('error');
                const errorEl = this.parentNode.querySelector('.field-error');
                if (errorEl) errorEl.remove();
            });
            
            field.addEventListener('change', function() {
                this.classList.remove('error');
                const errorEl = this.parentNode.querySelector('.field-error');
                if (errorEl) errorEl.remove();
            });
        });
        
        // NI Number formatting
        const niInput = form.querySelector('input[name="ni_number"]');
        if (niInput) {
            niInput.addEventListener('input', function() {
                let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                if (value.length > 9) value = value.substring(0, 9);
                this.value = value;
            });
        }
        
        // Phone number formatting
        const phoneInputs = form.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                let value = this.value.replace(/[^\d\s\+\-\(\)]/g, '');
                this.value = value;
            });
        });
        
        // Postcode formatting
        const postcodeInput = form.querySelector('input[name="postcode"]');
        if (postcodeInput) {
            postcodeInput.addEventListener('blur', function() {
                let value = this.value.toUpperCase().trim();
                // Basic UK postcode formatting
                value = value.replace(/\s+/g, ' ');
                this.value = value;
            });
        }
    }
    
    /**
     * Helper function to scroll to first error
     */
    window.scrollToFirstError = function() {
        const firstError = document.querySelector('.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    };
    
})();
