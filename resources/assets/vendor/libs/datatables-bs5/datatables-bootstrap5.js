import JSZip from 'jszip';
import pdfMake from 'pdfmake';
import * as pdfFonts from 'pdfmake/build/vfs_fonts';
// import { vfs } from 'pdfmake/build/vfs_fonts';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select-bs5';
import 'datatables.net-buttons';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';
import 'datatables.net-responsive';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowgroup-bs5';
import 'jquery-datatables-checkboxes';

DataTable.Buttons.jszip(JSZip);
DataTable.Buttons.pdfMake(pdfMake);
pdfMake.vfs = pdfFonts.pdfMake.vfs;
// Assign vfs to pdfMake
// pdfMake.vfs = vfs; // Correctly assign the imported vfs
