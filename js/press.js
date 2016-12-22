import React from 'react';
import ReactDOM from 'react-dom';
import PaymentTable from './components/PaymentTable';

// let payments = [{
//   uuid:'1',
//   request_date:'request_date',
//   register_name:'register_name',
//   manager_name:'manager_name',
//   manger_accept_datetime:'manager_accept_datetime',
//   co_accpeter_name:'co_accpeter_name',
//   co_accept_datetime:'co_accept_datetime',
//   month:'month',
//   team:'team',
//   product:'product',
//   category:'category',
//   desc:'desc',
//   company_name:'company_name',
//   price:'price',
//   pay_date:'pay_date',
//   tax:'tax',
//   tax_export:'tax_export',
//   tax_date:'tax_date',
//   is_account_book_registered:'is_account_book_registered',
//   bank:'bank',
//   bank_account:'bank_account',
//   bank_account_owner:'bank_account_owner',
//   note:'note',
//   paytype:'paytype',
//   status:'status'
// },{
//   uuid:'2',
//   request_date:'request_date',
//   register_name:'register_name',
//   manager_name:'manager_name',
//   manger_accept_datetime:'manager_accept_datetime',
//   co_accpeter_name:'co_accpeter_name',
//   co_accept_datetime:'co_accept_datetime',
//   month:'month',
//   team:'team',
//   product:'product',
//   category:'category',
//   desc:'desc',
//   company_name:'company_name',
//   price:'price',
//   pay_date:'pay_date',
//   tax:'tax',
//   tax_export:'tax_export',
//   tax_date:'tax_date',
//   is_account_book_registered:'is_account_book_registered',
//   bank:'bank',
//   bank_account:'bank_account',
//   bank_account_owner:'bank_account_owner',
//   note:'note',
//   paytype:'paytype',
//   status:'status'
// }];

let tableSchema = {
  'uuid': {name:'UUID', isKey:true},
  'request_date': {name:'요청일'},
  'register_name': {name:'요청자'},
  'manager_name': {name:'승인자'},
  'manger_accept_datetime': {name:'승인자 확인'},
  'co_accpeter_name': {name:'재무팀 확인'},
  'month': {name:'귀속월'},
  'team': {name:'귀속부서'},
  'product': {name:'프로덕트'},
  'category': {name:'분류'},
  'desc': {name:'상세내역'},
  'company_name': {name:'업체명'},
  'price': {name:'입금금액'},
  'pay_date': {name:'결제(예정)일'},
  'tax_export': {name:'세금계산서수취여부'},
  'tax_date': {name:'세금계산서일자'},
  'is_account_book_registered': {name:'장부반영여부'},
  'bank': {name:'입금은행'},
  'bank_account': {name:'입금계좌번호'},
  'bank_account_owner': {name:'예금주'},
  'note': {name:'비고'},
  'paytype': {name:'결제수단'},
  'status': {name:'상태'},
};

const rootElement = document.getElementById('root');

fetch('/payments/payments', {credentials: 'same-origin'})
  .then(
    response => response.json(),
    error => console.log('error1:', error)
  )
  .then(
    payments => {
      //console.log('payments=', payments);
      ReactDOM.render(
        <PaymentTable schema={tableSchema} datas={payments}>
          {/*<TableHead dataField='uuid' isKey>UUID</TableHead>*/}
          {/*<TableHead dataField='request_date'>요청일</TableHead>*/}
          {/*<TableHead dataField='register_name'>요청자</TableHead>*/}
          {/*<TableHead dataField='manager_name'>승인자</TableHead>*/}
          {/*<TableHead dataField='manger_accept_datetime'>승인자 확인</TableHead>*/}
          {/*<TableHead dataField='co_accpeter_name'>재무팀 확인</TableHead>*/}
          {/*<TableHead dataField='month'>귀속월</TableHead>*/}
          {/*<TableHead dataField='team'>귀속부서</TableHead>*/}
          {/*<TableHead dataField='product'>프로덕트</TableHead>*/}
          {/*<TableHead dataField='category'>분류</TableHead>*/}
          {/*<TableHead dataField='desc'>상세내역</TableHead>*/}
          {/*<TableHead dataField='company_name'>업체명</TableHead>*/}
          {/*<TableHead dataField='price'>입금금액</TableHead>*/}
          {/*<TableHead dataField='pay_date'>결제(예정)일</TableHead>*/}
          {/*<TableHead dataField='tax_export'>세금계산서수취여부</TableHead>*/}
          {/*<TableHead dataField='tax_date'>세금계산서일자</TableHead>*/}
          {/*<TableHead dataField='is_account_book_registered'>장부반영여부</TableHead>*/}
          {/*<TableHead dataField='bank'>입금은행</TableHead>*/}
          {/*<TableHead dataField='bank_account'>입금계좌번호</TableHead>*/}
          {/*<TableHead dataField='bank_account_owner'>예금주</TableHead>*/}
          {/*<TableHead dataField='note'>비고</TableHead>*/}
          {/*<TableHead dataField='paytype'>결제수단</TableHead>*/}
          {/*<TableHead dataField='status'>상태</TableHead>*/}
        </PaymentTable>,
        rootElement);
    },
    error => console.log('error2:', error)
);
