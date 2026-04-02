<script>
    $(function () {
        $('#cnpj').mask('00.000.000/0000-00', { clearIfNotMatch: false });
        $('#cep').mask('00000-000', { clearIfNotMatch: false });

        $('#cep').on('blur', function () {
            var cep = $(this).cleanVal();
            if (cep.length !== 8) {
                return;
            }
            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function (data) {
                if (data.erro) {
                    return;
                }
                $('#logradouro').val(data.logradouro || '');
                $('#bairro').val(data.bairro || '');
                $('#cidade').val(data.localidade || '');
                $('#uf').val(data.uf || '');
            });
        });

        $('form.form').on('submit', function () {
            var $cnpj = $('#cnpj');
            var $cep = $('#cep');
            $cnpj.val($cnpj.cleanVal());
            $cep.val($cep.cleanVal());
        });
    });
</script>
