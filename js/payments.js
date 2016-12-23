import React from 'react';
import { render } from 'react-dom';
import {Table, TableColumn} from './components/SearchSelectTable';



const rootElement = document.getElementById('root');

fetch('/payments/payments', {credentials: 'same-origin'})
  .then(
    response => response.json(),
    error => console.log('error1:', error)
  )
  .then(
    payments => {
      //console.log('payments=', payments);

      render(
        <Table datas={payments}>
          <TableColumn dataField='uuid' isKey>UUID</TableColumn>
          <TableColumn dataField='request_date'>요청일</TableColumn>
          <TableColumn dataField='register_name'>요청자</TableColumn>
          <TableColumn dataField='manager_name'>승인자</TableColumn>
          <TableColumn dataField='manger_accept_datetime'>승인자 확인</TableColumn>
          <TableColumn dataField='co_accpeter_name'>재무팀 확인</TableColumn>
          <TableColumn dataField='month'>귀속월</TableColumn>
          <TableColumn dataField='team'>귀속부서</TableColumn>
          <TableColumn dataField='product'>프로덕트</TableColumn>
          <TableColumn dataField='category'>분류</TableColumn>
          <TableColumn dataField='desc'>상세내역</TableColumn>
          <TableColumn dataField='company_name'>업체명</TableColumn>
          <TableColumn dataField='price'>입금금액</TableColumn>
          <TableColumn dataField='pay_date'>결제(예정)일</TableColumn>
          <TableColumn dataField='tax_export'>세금계산서수취여부</TableColumn>
          <TableColumn dataField='tax_date'>세금계산서일자</TableColumn>
          <TableColumn dataField='is_account_book_registered'>장부반영여부</TableColumn>
          <TableColumn dataField='bank'>입금은행</TableColumn>
          <TableColumn dataField='bank_account'>입금계좌번호</TableColumn>
          <TableColumn dataField='bank_account_owner'>예금주</TableColumn>
          <TableColumn dataField='note'>비고</TableColumn>
          <TableColumn dataField='paytype'>결제수단</TableColumn>
          <TableColumn dataField='status'>상태</TableColumn>
        </Table>,
        rootElement
      );
    },
    error => console.log('error2:', error)
);
