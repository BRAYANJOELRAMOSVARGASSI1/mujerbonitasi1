<?php
try { 
    \Illuminate\Support\Facades\Mail::raw('Test', function($m) {
        $m->to('joelramostrbj@gmail.com')->subject('Test Email');
    }); 
    echo 'Success'; 
} catch (\Exception $e) { 
    echo 'Error: ' . $e->getMessage(); 
}
