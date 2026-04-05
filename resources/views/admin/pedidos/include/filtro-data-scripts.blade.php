<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/pt.js"></script>
<script>
    $(document).ready(function() {
        // Seleção de elementos com jQuery
        var $periodoInput = $('#periodo_pedidos');
        var $hInicio = $('#data_inicio');
        var $hFim = $('#data_fim');

        // Verifica se os elementos existem antes de prosseguir
        if (!$periodoInput.length || !$hInicio.length || !$hFim.length) {
            return;
        }

        // Datas iniciais vindas do Blade (Laravel)
        var defaultDates = [];
        @if (request('data_inicio') && request('data_fim'))
            defaultDates = ['{{ request('data_inicio') }}', '{{ request('data_fim') }}'];
        @endif

        // Configuração de localização (Português)
        var pt = (typeof flatpickr.l10ns !== 'undefined') ? flatpickr.l10ns.pt : undefined;

        // Inicialização do Flatpickr
        $periodoInput.flatpickr({
            mode: 'range',
            dateFormat: 'Y-m-d',
            locale: pt,
            allowInput: true,
            altInput: true,
            altFormat: 'd/m/Y',
            defaultDate: defaultDates.length === 2 ? defaultDates : null,
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    $hInicio.val(this.formatDate(selectedDates[0], 'Y-m-d'));
                    $hFim.val(this.formatDate(selectedDates[1], 'Y-m-d'));
                } else if (selectedDates.length === 0) {
                    $hInicio.val('');
                    $hFim.val('');
                }
            },
            onClose: function(selectedDates) {
                if (selectedDates.length === 1) {
                    var d = this.formatDate(selectedDates[0], 'Y-m-d');
                    $hInicio.val(d);
                    $hFim.val(d);
                }
            }
        });
    });
</script>
