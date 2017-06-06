<?php
    include("header.php");
    include("funcoes.php");

    function calcula_tamanho ($alfa, $beta, $desv, $dif) {
        $grau_lib = 99;
        $beta_t = get_t(2*$beta, 2*$grau_lib);
        for ($i=0; $i<5; $i++) {
            $alfa_t = get_t($alfa, 2*$grau_lib);
            $tamanho = (($desv*$desv)/($dif*$dif))*(($alfa_t+$beta_t)*($alfa_t+$beta_t));
            $grau_lib = $tamanho - 1;
        }
        return $tamanho;
    }

    if(isset($_POST['calcular'])){
        if (is_numeric($_POST['t_desvio'])) {
            if (is_numeric($_POST['t_min_dif'])) { 
                if (is_numeric($_POST['t_alfa'])) {
                    if (is_numeric($_POST['t_beta'])) {

                        $desvio = $_POST['t_desvio'];
                        $min_dif = $_POST['t_min_dif'];
                        $alfa =  $_POST['t_alfa']/100;
                        $beta =  $_POST['t_beta']/100;

                        if ($alfa == 0 || $alfa == 1)
                            echo "<h3 class='erro'>O Alfa deve ser maior que 0 e menor que 100.</h3>";
                        else if ($beta == 0)
                            echo "<h3 class='erro'>O Beta deve ser maior que 0.</h3>";
                        else {
                            $n = calcula_tamanho($alfa, $beta, $desvio, $min_dif);
                            $n_anterior = $n;

                            if(isset($_POST['finita'])){
                                if (is_numeric($_POST['t_finita'])) {
                                    $finita = $_POST['t_finita'];
                                    if($finita < 1) echo "<h3 class='erro'>Valor inválido para a População.</h3>";
                                    else {
                                        $n_finita = $n_anterior/(1+($n_anterior-1)/$finita);
                                        $n_anterior = $n_finita;
                                    }
                                }
                                else echo "<h3 class='erro'>Preencha todos os campos.</h3>";
                            }
                            if(isset($_POST['desenho'])){
                                if (is_numeric($_POST['t_desenho'])) {
                                    $desenho = $_POST['t_desenho'];
                                    if($desenho < 1 || $desenho > 9) echo "<h3 class='erro'>Valor inválido para o Efeito do Desenho.</h3>";
                                    else {
                                        $n_desenho = $n_anterior*$desenho;
                                        $n_anterior = $n_desenho;
                                    }
                                }
                                else echo "<h3 class='erro'>Preencha todos os campos.</h3>";
                            }
                            if(isset($_POST['perda'])){
                                if (is_numeric($_POST['t_perda'])) {
                                    $perda = $_POST['t_perda']/100;
                                    if($perda < 0 || $perda > 100) echo "<h3 class='erro'>Valor inválido para a Perda de Elementos.</h3>";
                                    else {
                                        $n_perda = $n_anterior/(1-$perda);
                                        $n_anterior = $n_perda;
                                    }
                                }
                                else echo "<h3 class='erro'>Preencha todos os campos.</h3>";
                            }
                        }
                    }
                    else echo "<h3 class='erro'>Valor inválido para o Beta.</h3>";
                }
                else echo "<h3 class='erro'>Valor inválido para o Alfa.</h3>";
            }
            else echo "<h3 class='erro'>Valor inválido para a Mínima Diferença.</h3>";
        }
        else echo "<h3 class='erro'>Valor inválido para o Desvio Padrão.</h3>";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        h3.erro{text-align: center; color: #FF0000;}
        h3.titulo{text-align: center; color: #666;}
        h2.titulo{text-align: center; color: #666;}
        div#content {
            width: 550px;
            margin: auto;
            background: #ccc;
        }

        form{text-align: center; margin-top: 10px;}
        input[type="text"]{border: 1px solid #CCC; width: 100px; height: 25px; padding-left: 10px; border-radius: 3px; margin-top: 10px;}
        input[type="number"]{border: 1px solid #CCC; width: 100px; height: 25px; padding-left: 10px; border-radius: 3px; margin-top: 10px;}
        input[type="submit"]{background-color: #4169E1; color: #FFF; border: none; width: 80px; height: 30px; margin-top: 20px; border-radius: 3px;}
        input[type="submit"]:hover{background-color: #001F3F; cursor: pointer;}
    </style>
</head>
<body>
    
    <div id="content">
        <br />
        <h2 class="titulo">Tamanho da Amostra</h2>
        <h3 class="titulo">Diferença entre 2 Médias com Grupos Dependentes</h3>
        
        <form method="POST">

            <?php $desvio = (!empty($_POST['t_desvio']) ? $_POST['t_desvio'] : ''); ?>
            <label for="t_desvio" title="Qual o valor estimado do desvio padrão da diferença entre as duas populações estudadas. Não é o desvio padrão da população, mas sim da diferença entre as duas populações. Pode ser estimado pela literatura ou por um piloto.">Estimativa do Desvio Padrão da Diferença:</label>
            <input type="number" id="t_desvio" name="t_desvio" min="0" step="any" value="<?php echo $desvio; ?>"><br />

            <?php $min_dif = (!empty($_POST['t_min_dif']) ? $_POST['t_min_dif'] : ''); ?>
            <label for="t_min_dif" title="Qual a menor diferença entre as duas populações que tem relevância clínica e, portanto, é importante provar que existe diferença entre as populações.">Mínima Diferença a ser Detectada:</label>
            <input type="number" id="t_min_dif" name="t_min_dif" min="0" step="any" value="<?php echo $min_dif; ?>"><br />

            <?php $alfa = (!empty($_POST['t_alfa']) ? $_POST['t_alfa'] : ''); ?>
            <label for="t_alfa" title="É o valor do nível de significância que você irá adotar na pesquisa. O usual é 5%, porém você pode escolher qualquer valor entre 0% e 100%.">Alfa (%):</label>
            <input type="number" id="t_alfa" name="t_alfa" min="0" max="100" step="any" value="<?php echo $alfa; ?>"><br />

            <?php $beta = (!empty($_POST['t_beta']) ? $_POST['t_beta'] : ''); ?>
            <label for="t_beta" title="É o valor do nível do erro beta que você admite na pesquisa. O usual é 20%, porém você pode escolher qualquer valor entre 0% e 50%.">Beta (%):</label>
            <input type="number" id="t_beta" name="t_beta" min="0" max="50" step="any" value="<?php echo $beta; ?>"><br />

            <input type="submit" value="Calcular" name="calcular"><br />

            <?php if (empty($n)) $n = ''; ?>
            <label for="n" title="Tamanho da amostra.">N:</label>
            <input type="text" id="n" name="n" readonly value="<?php echo ceil($n); ?>"><br />

            <div>
                <input type="checkbox" name="finita" id="finita" value="finita" <?php echo (isset($_POST['finita'])) ? "checked" : null; ?>>
                <?php $finita = (!empty($_POST['t_finita']) ? $_POST['t_finita'] : '999999999'); ?>
                <label for="finita" title="Entre com o tamanho da população para o qual você está calculando a amostra. Se não souber, ou a população for maior do que 5.000 elementos, não entre valor algum que o tamanho da amostra será calculado para população infinita.">População finita:</label>
                <input type="number" id="t_finita" name="t_finita" min="0" step="any" value="<?php echo $finita; ?>">
                <?php if (empty($n_finita)) $n_finita = ''; ?>
                <label for="n_finita" title="Tamanho da amostra considerando população finita.">N:</label>
                <input type="text" id="n_finita" name="n_finita" readonly value="<?php echo ceil($n_finita); ?>"><br />

                <input type="checkbox" name="desenho" id="desenho" value="desenho" <?php echo (isset($_POST['desenho'])) ? "checked" : null; ?>>
                <?php $desenho = (!empty($_POST['t_desenho']) ? $_POST['t_desenho'] : '1'); ?>
                <label for="desenho" title="Se quiser levar em conta o fato de que a amostragem não será aleatória simples entre com um fator para correção. Deve estar entre 1 e 9. Normalmente o valor utilizado é entre 1,2 e 2.">Efeito do desenho:</label>
                <input type="number" id="t_desenho" name="t_desenho" min="1" max="9" step="any" value="<?php echo $desenho; ?>">
                <?php if (empty($n_desenho)) $n_desenho = ''; ?>
                <label for="n_desenho" title="Tamanho da amostra considerando efeito do desenho.">N:</label>
                <input type="text" id="n_desenho" name="n_desenho" readonly value="<?php echo ceil($n_desenho); ?>"><br />

                <input type="checkbox" name="perda" id="perda" value="perda" <?php echo (isset($_POST['perda'])) ? "checked" : null; ?>>
                <?php $perda = (!empty($_POST['t_perda']) ? $_POST['t_perda'] : '0'); ?>
                <label for="perda" title="Se após a seleção da amostra houver possibilidade de perda de elementos é necessário estimar quantos por cento poderá ocorrer de perda durante a pesquisa.">Perda de elementos (%):</label>
                <input type="number" id="t_perda" name="t_perda" min="0" max="100" step="any" value="<?php echo $perda; ?>">
                <?php if (empty($n_perda)) $n_perda = ''; ?>
                <label for="n_perda" title="Tamanho da amostra considerando perda de elementos.">N:</label>
                <input type="text" id="n_perda" name="n_perda" readonly value="<?php echo ceil($n_perda); ?>"><br />
            </div>
            <br />
        </form>
    </div>
</body>
</html>