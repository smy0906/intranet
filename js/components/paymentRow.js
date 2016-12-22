import React from 'react';
import {Button, Checkbox} from 'react-bootstrap';

class PaymentRow extends React.Component {

  render() {
    const payment = this.props.data;

    return (
      <tr>
        <td><Checkbox checked={this.props.selected}
                      onChange={this.props.onSelect}/></td>
        <td>{payment.uuid}</td>
        <td>{payment.request_date}</td>
        <td>{payment.register_name}</td>
        <td>{payment.manager_name}</td>
        <td>{payment.manger_accept_datetime}</td>
        <td>{payment.co_accpeter_name}</td>
        <td>{payment.month}</td>
        <td>{payment.team}</td>
        <td>{payment.product}</td>
        <td>{payment.category}</td>
        <td>{payment.desc}</td>
        <td>{payment.company_name}</td>
        <td>{payment.price}</td>
        <td>{payment.pay_date}</td>
        <td>{payment.tax_export}</td>
        <td>{payment.tax_date}</td>
        <td>{payment.is_account_book_registered}</td>
        <td>{payment.bank}</td>
        <td>{payment.bank_account}</td>
        <td>{payment.bank_account_owner}</td>
        <td>{payment.note}</td>
        <td>{payment.paytype}</td>
        <td>{payment.status}</td>
        <td><Button onClick={this.props.onEdit}>편집</Button></td>
        <td><Button onClick={this.props.onDel}>삭제</Button></td>
      </tr>
    );
  }
}

export default PaymentRow;
