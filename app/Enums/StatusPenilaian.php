<?php

namespace App\Enums;

enum StatusPenilaian: string
{
    case BELUM_DINILAI = 'belum dinilai';
    case SEDANG_DINILAI = 'sedang dinilai';
    case SUDAH_DINILAI = 'sudah dinilai';
}
