<?php

class AddressController {
    public function validate() {
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? $_POST['q'] ?? $_GET['address'] ?? $_POST['address'] ?? '');
        if ($q === '') {
            echo json_encode(['ok' => false, 'message' => 'Dirección vacía']);
            return;
        }

        $country = trim($_GET['country'] ?? $_POST['country'] ?? '');
        $params = [
            'format' => 'jsonv2',
            'limit' => 5,
            'addressdetails' => 1,
            'q' => $q,
        ];
        if ($country) {
            $params['countrycodes'] = strtolower($country);
        } else {
            // Por defecto limitar a Chile si no se especifica
            $params['countrycodes'] = 'cl';
        }

        $query = http_build_query($params);
        $url = 'https://nominatim.openstreetmap.org/search?' . $query;

        $ch = curl_init($url);
        $appUrl = getenv('APP_URL') ?: 'http://localhost:8081';
        $userAgent = 'CoffeeShopApp/1.0 (+'.$appUrl.')';
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: es-CL,es;q=0.9,en;q=0.8',
            ],
            CURLOPT_USERAGENT => $userAgent,
        ]);

        $raw = curl_exec($ch);
        if ($raw === false) {
            echo json_encode(['ok' => false, 'message' => 'No se pudo verificar la dirección']);
            return;
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            echo json_encode(['ok' => false, 'message' => 'Servicio de geocodificación no disponible']);
            return;
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || count($data) === 0) {
            echo json_encode(['ok' => false, 'message' => 'No se encontraron coincidencias']);
            return;
        }

        $suggestions = [];
        foreach ($data as $row) {
            $suggestions[] = [
                'display_name' => $row['display_name'] ?? '',
                'lat' => isset($row['lat']) ? (float)$row['lat'] : null,
                'lon' => isset($row['lon']) ? (float)$row['lon'] : null,
                'address' => $row['address'] ?? [],
            ];
        }
        $best = $suggestions[0];

        echo json_encode([
            'ok' => true,
            'result' => $best,
            'suggestions' => $suggestions,
        ]);
    }
}
