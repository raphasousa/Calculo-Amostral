<?php
    include("header.php");
    include("funcoes.php");

    if(isset($_POST['calcular'])){
        if (is_numeric($_POST['t_controle'])) {
            if (is_numeric($_POST['t_experimental'])) {
                if (is_numeric($_POST['t_alfa'])) {
                    if (is_numeric($_POST['t_beta'])) {
                        if (is_numeric($_POST['t_k'])) {
                            $alfa =  $_POST['t_alfa'];
                            $beta =  $_POST['t_beta'];
                            $k =  $_POST['t_k'];
                            $p_controle = $_POST['t_controle']/100;
                            $p_experimental = $_POST['t_experimental']/100;

                            if ($alfa == 0 || $alfa == 100)
                                echo "<h3 class='erro'>O Alfa deve ser maior que 0 e menor que 100.</h3>";
                            else if ($beta == 0 || $beta == 100)
                                echo "<h3 class='erro'>O Beta deve ser maior que 0 e menor que 100.</h3>";
                            else if ($p_controle == 0 || $p_controle == 1)
                                echo "<h3 class='erro'>A Proporção Controle deve ser maior que 0 e menor que 100.</h3>";
                            else if ($p_experimental == 0 || $p_experimental == 1)
                                echo "<h3 class='erro'>A Proporção Experimental deve ser maior que 0 e menor que 100.</h3>";
                            else if ($k == 0)
                                echo "<h3 class='erro'>O valor de K deve ser diferente de 0.</h3>";
                            else {
                                $z_alfa = get_z_tabela($alfa, "alfa");
                                $z_beta = get_z_tabela($beta, "beta");

                                $odds_ratio = round( ($p_experimental*(1-$p_controle)) / ($p_controle*(1-$p_experimental)), 4);

                                $parte1 = (($z_alfa+$z_beta)*($z_alfa+$z_beta)) / (log($odds_ratio)*log($odds_ratio));
                                $parte2 = ((1/(($k*$p_experimental)*(1-$p_experimental)))+(1/($p_controle*(1-$p_controle))));

                                $n = $parte1 * $parte2;
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
                        else echo "<h3 class='erro'>Valor inválido para o K.</h3>";
                    }
                    else echo "<h3 class='erro'>Valor inválido para o Beta.</h3>";
                }
                else echo "<h3 class='erro'>Valor inválido para o Alfa.</h3>";
            }
            else echo "<h3 class='erro'>Valor inválido para a Proporção Experimental.</h3>";
        }
        else echo "<h3 class='erro'>Valor inválido para a Proporção Controle.</h3>";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <script src="js/statcommon.js"></script>
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
        <h3 class="titulo">Provar que Odds Ratio é Diferente de 1</h3>
        
        <form method="POST">
            <?php $p_controle = (!empty($_POST['t_controle']) ? $_POST['t_controle'] : ''); ?>
            <label for="t_controle" title="Porcentagem da população total usada como controle.">Proporção Controle (%):</label>
            <input type="number" id="t_controle" name="t_controle" min="0" max="100" step="any" value="<?php echo $p_controle; ?>"><br />

            <?php $p_experimental = (!empty($_POST['t_experimental']) ? $_POST['t_experimental'] : ''); ?>
            <label for="t_experimental" title="Porcentagem da população total usada para experimento.">Proporção Experimental (%):</label>
            <input type="number" id="t_experimental" name="t_experimental" min="0" max="100" step="any" value="<?php echo $p_experimental; ?>"><br />

            <?php $k = (!empty($_POST['t_k']) ? $_POST['t_k'] : ''); ?>
            <label for="t_k" title="Proporção entre n_experimental e n_controle.">K (n exp / n cont):</label>
            <input type="number" id="t_k" name="t_k" min="0" step="any" value="<?php echo $k; ?>"><br />

            <?php $alfa = (!empty($_POST['t_alfa']) ? $_POST['t_alfa'] : ''); ?>
            <label for="t_alfa" title="É o valor do nível de significância que você irá adotar na pesquisa. O usual é 5%, porém você pode escolher qualquer valor entre 0% e 100%.">Alfa (%):</label>
            <input type="number" id="t_alfa" name="t_alfa" min="0" max="100" step="any" value="<?php echo $alfa; ?>"><br />

            <?php $beta = (!empty($_POST['t_beta']) ? $_POST['t_beta'] : ''); ?>
            <label for="t_beta" title="É o valor do nível do erro beta que você admite na pesquisa. O usual é 20%, porém você pode escolher qualquer valor entre 0% e 100%.">Beta (%):</label>
            <input type="number" id="t_beta" name="t_beta" min="0" max="100" step="any" value="<?php echo $beta; ?>"><br />

            <input type="submit" value="Calcular" name="calcular"><br />

            <?php if (empty($odds_ratio)) $odds_ratio = ''; ?>
            <label for="odds_ratio" title="Odds Ratio Esperado.">Odds Ratio Esperado:</label>
            <input type="text" id="odds_ratio" name="odds_ratio" readonly value="<?php echo $odds_ratio; ?>"><br />

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
    <br /><br />
</body>
</html>