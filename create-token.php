
<?php
/*

    private function createToken()
    {
        $array = array('random_number', 'random_uppercase', 'random_lowercase');
        $token = '';
        for ($i = 0; $i < 5; $i++) {
            for ($ii = 0; $ii < 5; $ii++) {
                $call = rand(0, 2);
                $token .= $this->$array[ $call ]();
            }
            $token .= '-';
        }

        return rtrim($token, '-');
    }

    private function random_number($n = 1)
    {
        $out = '';
        for ($i = 0; $i < $n; $i++) {
            $out .= rand(0, 9);
        }

        return $out;
    }

    private function random_uppercase($n = 1)
    {
        $out = '';
        for ($i = 0; $i < $n; $i++) {
            $out .= chr(rand(65, 90));
        }

        return $out;
    }


    private function random_lowercase($n = 1)
    {
        $out = '';
        for ($i = 0; $i < $n; $i++) {
            $out .= chr(rand(97, 122));
        }

        return $out;
    }

