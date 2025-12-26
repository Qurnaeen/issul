// Custom JavaScript untuk PPDB System

// Document Ready
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize DataTables if exists
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 20,
            responsive: true
        });
    }
});

// Form Validation Helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return false;
    }
    return true;
}

// File Upload Preview
function previewFile(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (file.type.startsWith('image/')) {
                preview.innerHTML = `<img src="${e.target.result}" class="file-preview" alt="Preview">`;
            } else if (file.type === 'application/pdf') {
                preview.innerHTML = `<i class="bi bi-file-pdf text-danger" style="font-size: 5rem;"></i><br>${file.name}`;
            } else {
                preview.innerHTML = `<i class="bi bi-file-earmark text-secondary" style="font-size: 5rem;"></i><br>${file.name}`;
            }
        };
        
        reader.readAsDataURL(file);
    }
}

// Validate File Size and Type
function validateFile(input, maxSize = 2097152, allowedTypes = ['application/pdf', 'image/jpeg', 'image/png']) {
    const file = input.files[0];
    
    if (!file) {
        return { valid: false, message: 'Pilih file terlebih dahulu' };
    }
    
    // Check size
    if (file.size > maxSize) {
        const maxMB = maxSize / 1048576;
        return { valid: false, message: `Ukuran file maksimal ${maxMB} MB` };
    }
    
    // Check type
    if (!allowedTypes.includes(file.type)) {
        return { valid: false, message: 'Tipe file tidak diizinkan. Hanya PDF, JPG, PNG' };
    }
    
    return { valid: true, message: 'File valid' };
}

// SweetAlert Wrapper
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

function showToast(icon, title) {
    Toast.fire({
        icon: icon,
        title: title
    });
}

function showConfirm(title, text, confirmText = 'Ya', cancelText = 'Batal') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText
    });
}

// Loading Overlay
function showLoading() {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'spinner-overlay';
    overlay.innerHTML = `
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Format Number
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Format Currency
function formatCurrency(num) {
    return 'Rp ' + formatNumber(num);
}

// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('success', 'Berhasil disalin');
    }, function() {
        showToast('error', 'Gagal menyalin');
    });
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    $('.alert:not(.alert-permanent)').fadeOut('slow');
}, 5000);

// Confirm Delete
function confirmDelete(url, message = 'Data yang dihapus tidak dapat dikembalikan!') {
    showConfirm('Apakah Anda yakin?', message, 'Ya, Hapus!').then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Print Page
function printPage() {
    window.print();
}

// Export Table to Excel (simple version)
function exportTableToExcel(tableId, filename = 'data') {
    const table = document.getElementById(tableId);
    const html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename + '.xls';
    link.click();
}

// Multi-step Form Handler
class MultiStepForm {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.steps = this.form.querySelectorAll('.form-step');
        this.currentStep = 0;
        this.updateStepIndicator();
    }
    
    nextStep() {
        if (this.currentStep < this.steps.length - 1) {
            this.steps[this.currentStep].classList.remove('active');
            this.currentStep++;
            this.steps[this.currentStep].classList.add('active');
            this.updateStepIndicator();
        }
    }
    
    prevStep() {
        if (this.currentStep > 0) {
            this.steps[this.currentStep].classList.remove('active');
            this.currentStep--;
            this.steps[this.currentStep].classList.add('active');
            this.updateStepIndicator();
        }
    }
    
    updateStepIndicator() {
        const indicators = document.querySelectorAll('.step');
        indicators.forEach((indicator, index) => {
            if (index < this.currentStep) {
                indicator.classList.add('completed');
                indicator.classList.remove('active');
            } else if (index === this.currentStep) {
                indicator.classList.add('active');
                indicator.classList.remove('completed');
            } else {
                indicator.classList.remove('active', 'completed');
            }
        });
    }
}
