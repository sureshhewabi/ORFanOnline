<?php

return [


    /*
    |--------------------------------------------------------------------------
    | full file path where blastp programme installed in local computer
    |--------------------------------------------------------------------------
    |
    */

    'blastprogram' => '/usr/local/ncbi/blast/bin/blastp',

    /*
    |--------------------------------------------------------------------------
    | Bash script file that extracts the Gene IDs from the fasta file
    |--------------------------------------------------------------------------
    |
    */

    'extractIdsFromFasta' => '/Applications/XAMPP/xamppfiles/htdocs/ORFanOnline/public/scripts/extractIdsFromFasta',

    /*
    |--------------------------------------------------------------------------
    | Full file path where ORFanFinder programme installed in local computer
    |--------------------------------------------------------------------------
    |
    */

    'ORFanFinder' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/src/ORFanFinder/ORFanFinder',

    /*
    |--------------------------------------------------------------------------
    | ORFanFInder Database
    |--------------------------------------------------------------------------
    |
    */

    'database' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/databases/uniBacteria.hdb'

];
