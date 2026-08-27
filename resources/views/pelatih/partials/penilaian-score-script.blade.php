<script>
(function () {
    const MIN_SCORE = 0;
    const MAX_SCORE = 100;

    function clampPenilaianScore(raw) {
        if (raw === '' || raw === null || raw === undefined) {
            return 0;
        }
        let value = parseFloat(String(raw).replace(',', '.'));
        if (Number.isNaN(value)) {
            return 0;
        }
        value = Math.min(MAX_SCORE, Math.max(MIN_SCORE, value));
        return Math.round(value * 100) / 100;
    }

    function formatPenilaianScore(value) {
        return clampPenilaianScore(value).toFixed(2);
    }

    function bindPenilaianInputs(root) {
        const scope = root || document;
        scope.querySelectorAll('.penilaian-score-input').forEach(function (input) {
            if (input.dataset.penilaianBound === '1') {
                return;
            }
            input.dataset.penilaianBound = '1';

            input.addEventListener('input', function () {
                if (this.value === '' || this.value === '-') {
                    return;
                }
                const parts = String(this.value).split('.');
                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('').slice(0, 2);
                } else if (parts[1] && parts[1].length > 2) {
                    this.value = parts[0] + '.' + parts[1].slice(0, 2);
                }
                const num = parseFloat(this.value);
                if (!Number.isNaN(num) && (num > MAX_SCORE || num < MIN_SCORE)) {
                    this.value = formatPenilaianScore(this.value);
                }
            });

            input.addEventListener('blur', function () {
                this.value = formatPenilaianScore(this.value === '' ? 0 : this.value);
            });

            input.addEventListener('keydown', function (e) {
                if (['e', 'E', '+', '-'].includes(e.key)) {
                    e.preventDefault();
                }
            });
        });
    }

    function validatePenilaianForm(form) {
        let valid = true;
        form.querySelectorAll('.penilaian-score-input').forEach(function (input) {
            input.value = formatPenilaianScore(input.value === '' ? 0 : input.value);
            const value = parseFloat(input.value);
            if (Number.isNaN(value) || value < MIN_SCORE || value > MAX_SCORE) {
                valid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        return valid;
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindPenilaianInputs(document);

        document.querySelectorAll('form').forEach(function (form) {
            if (!form.querySelector('.penilaian-score-input')) {
                return;
            }
            form.addEventListener('submit', function (e) {
                bindPenilaianInputs(form);
                if (!validatePenilaianForm(form)) {
                    e.preventDefault();
                    alert('Nilai penilaian harus antara 0,00 hingga 100,00 (maksimal 2 angka di belakang koma).');
                }
            });
        });
    });

    window.SipeketPenilaian = {
        clamp: clampPenilaianScore,
        format: formatPenilaianScore,
        bind: bindPenilaianInputs,
    };
})();
</script>
