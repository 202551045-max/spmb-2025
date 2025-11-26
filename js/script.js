// Validasi Form
function validateForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    return isValid;
}

// Validasi Email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validasi Nomor HP
function validatePhone(phone) {
    const re = /^[0-9]{10,13}$/;
    return re.test(phone);
}

// Validasi NIK (16 digit)
function validateNIK(nik) {
    const re = /^[0-9]{16}$/;
    return re.test(nik);
}

// Validasi NISN (10 digit)
function validateNISN(nisn) {
    const re = /^[0-9]{10}$/;
    return re.test(nisn);
}

// Preview Image sebelum upload
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Konfirmasi sebelum hapus
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Format Rupiah
function formatRupiah(angka, prefix = 'Rp ') {
    const numberString = angka.toString().replace(/[^,\d]/g, '');
    const split = numberString.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
    if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
}

// Auto Calculate Rata-rata Nilai
function calculateAverage() {
    const mat = parseFloat(document.getElementById('nilai_matematika').value) || 0;
    const indo = parseFloat(document.getElementById('nilai_bahasa_indonesia').value) || 0;
    const ing = parseFloat(document.getElementById('nilai_bahasa_inggris').value) || 0;
    const ipa = parseFloat(document.getElementById('nilai_ipa').value) || 0;
    
    const average = ((mat + indo + ing + ipa) / 4).toFixed(2);
    document.getElementById('rata_rata_nilai').value = average;
}

// Show/Hide Password
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

// Countdown Timer untuk Pendaftaran
function countdownTimer(endDate, elementId) {
    const countDownDate = new Date(endDate).getTime();
    
    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById(elementId).innerHTML = 
            days + " hari " + hours + " jam " + minutes + " menit " + seconds + " detik";
        
        if (distance < 0) {
            clearInterval(x);
            document.getElementById(elementId).innerHTML = "PENDAFTARAN DITUTUP";
        }
    }, 1000);
}

// Search Table
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        let txtValue = tr[i].textContent || tr[i].innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            tr[i].style.display = '';
        } else {
            tr[i].style.display = 'none';
        }
    }
}

// Sort Table
function sortTable(tableId, columnIndex) {
    const table = document.getElementById(tableId);
    let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    switching = true;
    dir = 'asc';
    
    while (switching) {
        switching = false;
        rows = table.rows;
        
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName('TD')[columnIndex];
            y = rows[i + 1].getElementsByTagName('TD')[columnIndex];
            
            if (dir == 'asc') {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == 'desc') {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == 'asc') {
                dir = 'desc';
                switching = true;
            }
        }
    }
}

// Print Page
function printPage() {
    window.print();
}

// Export Table to Excel
function exportTableToExcel(tableId, filename = 'data') {
    const table = document.getElementById(tableId);
    const html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename + '.xls';
    link.click();
}

// Show Loading
function showLoading(buttonId) {
    const btn = document.getElementById(buttonId);
    btn.disabled = true;
    btn.innerHTML = '<span class="loading"></span> Memproses...';
}

// Hide Loading
function hideLoading(buttonId, originalText) {
    const btn = document.getElementById(buttonId);
    btn.disabled = false;
    btn.innerHTML = originalText;
}

// Auto resize textarea
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

// Copy to Clipboard
function copyToClipboard(text) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    alert('Nomor pendaftaran telah disalin: ' + text);
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Back to Top Button
window.addEventListener('scroll', function() {
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        if (window.pageYOffset > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    }
});

// Initialize tooltips (if using Bootstrap or similar)
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    console.log('PPDB SMK Rohmatul Ummah - System Ready');
});
