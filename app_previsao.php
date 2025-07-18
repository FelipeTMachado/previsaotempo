<?php
// Exemplo básico de aplicação PHP para previsão do nível do rio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $input['data'];
    $cmd = "python3 predict.py '" . json_encode($data) . "'";
    $output = shell_exec($cmd);
    // Log para debug
    $log = date('c') . "\n";
    $log .= "DATA: " . json_encode($data) . "\n";
    $log .= "CMD: $cmd\n";
    $log .= "OUTPUT: $output\n";
    file_put_contents('/tmp/debug_php_predict.log', $log, FILE_APPEND);
    if ($output === null || $output === false || trim($output) === '') {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao executar o modelo. Veja o log em /tmp/debug_php_predict.log']);
    } else {
        echo json_encode(['previsao' => floatval($output)]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsão do Nível do Rio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md mt-8">
        <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">Previsão do Nível do Rio</h1>
        <form id="form" class="space-y-4">
            <div id="inputs"></div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">Prever</button>
        </form>
        <div id="resultado" class="mt-6 text-center text-lg font-medium text-green-700"></div>
    </div>
    <script>
    // Features e labels customizados
    // Níveis do rio em metros (m), chuvas em milímetros (mm)
    const features = [
        { key: "NívelItuporanga", label: "Nível do Rio em Ituporanga (m, ex: 8.5)", tipo: "nivel" },
        { key: "ChuvaItuporanga", label: "Chuva em Ituporanga (mm, ex: 12)", tipo: "chuva" },
        { key: "NívelTaió", label: "Nível do Rio em Taió (m, ex: 9.2)", tipo: "nivel" },
        { key: "ChuvaTaió", label: "Chuva em Taió (mm, ex: 7)", tipo: "chuva" }
    ];
    const inputsDiv = document.getElementById('inputs');
    features.forEach(f => {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex flex-col mb-4';
        const label = document.createElement('label');
        label.innerText = f.label;
        label.className = 'mb-1 text-gray-700 font-medium';
        const input = document.createElement('input');
        input.type = 'number';
        input.name = f.key;
        input.step = 'any';
        input.className = 'border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400';
        wrapper.appendChild(label);
        wrapper.appendChild(input);
        inputsDiv.appendChild(wrapper);
    });
    document.getElementById('form').onsubmit = async function(e) {
        e.preventDefault();
        const data = {};
        features.forEach(f => {
            let val = document.getElementsByName(f.key)[0].value;
            if (val === '' || isNaN(val)) {
                data[f.key] = 0;
            } else {
                if (f.tipo === 'nivel') {
                    // Converter metros para decímetros (1m = 10dm)
                    data[f.key] = parseFloat(val) * 10;
                } else if (f.tipo === 'chuva') {
                    // Chuva já está em mm, manter
                    data[f.key] = parseFloat(val);
                }
            }
        });
        const res = await fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({data})
        });
        const json = await res.json();
        if (json.previsao !== undefined) {
            // Converter decímetros para metros para exibir
            const metros = (parseFloat(json.previsao) / 10).toFixed(2);
            document.getElementById('resultado').innerHTML = '<span class="font-bold">Previsão do nível do rio em Rio do Sul:</span><br><span class="text-2xl">' + metros + ' m</span>';
        } else if (json.erro) {
            document.getElementById('resultado').innerText = 'Erro: ' + json.erro;
        }
    }
    </script>
</body>
</html>
