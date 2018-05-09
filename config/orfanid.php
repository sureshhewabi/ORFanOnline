<?php

return [

    # full file path where blastp programme installed in local computer
    #'blastprogram' => '/usr/local/ncbi/blast/bin/blastp',
    'blastprogram' => '/home/vinodh/ncbi-blast-2.6.0+/bin/blastp',

    # Bash script file that extracts the Gene IDs from the fasta file
   # 'extractIdsFromFasta' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/scripts/extractIdsFromFasta',
    'extractIdsFromFasta' => '/home/vinodh/ORFanFinder-1.1.2/scripts/extractIdsFromFasta',

    # Full file path where ORFanFinder programme installed in local computer
    #'ORFanFinder' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/src/ORFanFinder/ORFanFinder',
    'ORFanFinder' => '/home/vinodh/ORFanFinder-1.1.2/src/ORFanFinder/ORFanFinder',

    # ORFanFInder Database
    #'database' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/databases/uniBacteria.hdb',
    'database' => '/home/vinodh/ORFanFinder-1.1.2/databases/nr.hdb',

    #node File
    #'nodefile' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/inputData/nodes',
    'nodefile' => '/home/vinodh/ORFanFinder-1.1.2/inputData/nodes',

    #name File
    #'namefile' => '/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/inputData/names',
    'namefile' => '/home/vinodh/ORFanFinder-1.1.2/inputData/names',

    # maximum expected value/evalue
    'default_maxevalue' => '1e-3',

    # maximum number of target sequence results(number of hits)
    'default_maxtargetseq' => '1000',

     # Clamp project bioinformatics pipeline developed in R language
   # 'clamp_rscript' => '/Users/suresh/Desktop/Script.R',
    'clamp_rscript' => '/var/www/html/ORFanOnline/public/data/Script.R'
];
