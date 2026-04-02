<script>
    $(function () {
        $('#preco').mask("#.##0,00", {reverse: true});
        $('#quantidade').mask('###0', { clearIfNotMatch: false });

        function parseBrDecimal(val) {
            if (val == null || String(val).trim() === '') {
                return NaN;
            }
            const normalized = String(val).replace(/\./g, '').replace(',', '.');
            const n = parseFloat(normalized);
            return Number.isFinite(n) ? n : NaN;
        }

        function parseQuantidade(val) {
            if (val == null || String(val).trim() === '') {
                return NaN;
            }
            const n = parseInt(String(val).replace(/\D/g, ''), 10);
            return Number.isFinite(n) ? n : NaN;
        }

        function calcularTotal() {
            const preco = parseBrDecimal($('#preco').val());
            const quantidade = parseQuantidade($('#quantidade').val());
            if (!Number.isFinite(preco) || !Number.isFinite(quantidade)) {
                return;
            }
            $('#total').val((preco * quantidade).toFixed(2));
            maskTotal();
        }

        function maskTotal() {
            $('#total').mask("#.##0,00", {reverse: true});
        }

        $('#preco').on('input', calcularTotal);
        $('#quantidade').on('input', calcularTotal);
    });
</script>
