(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/print"],{

/***/ "./resources/js/print.js":
/*!*******************************!*\
  !*** ./resources/js/print.js ***!
  \*******************************/
/***/ (() => {

window.printTickets = function (element) {
  var printWindow = window.open('', '', 'height=400,width=800');
  printWindow.document.write('<html><body>');
  printWindow.document.write(element.innerHTML);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
  printWindow.close();
};

/***/ })

},
0,[["./resources/js/print.js","/manifest"]]]);