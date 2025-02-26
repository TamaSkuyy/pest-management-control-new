import JSZip from 'jszip';
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

import pdfMake from 'pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

// Initialize DataTables plugins
window.JSZip = JSZip;
DataTable.Buttons.jszip(JSZip);
window.pdfMake = pdfMake;
DataTable.Buttons.pdfMake(pdfMake);
// pdfMake.vfs = pdfFonts.pdfMake.vfs;
pdfMake.addVirtualFileSystem(pdfFonts);
