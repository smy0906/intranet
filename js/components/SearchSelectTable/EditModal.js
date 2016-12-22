import React from 'react';
import { Button, Modal } from 'react-bootstrap';

class EditModal extends React.Component {
  render() {
    let data = {
      uuid:Math.random(),
      request_date:'request_date',
      register_name:'register_name',
      manager_name:'manager_name',
      manger_accept_datetime:'manager_accept_datetime',
      co_accpeter_name:'co_accpeter_name',
      co_accept_datetime:'co_accept_datetime',
      month:'month',
      team:'team',
      product:'product',
      category:'category',
      desc:'desc',
      company_name:'company_name',
      price:'price',
      pay_date:'pay_date',
      tax:'tax',
      tax_export:'tax_export',
      tax_date:'tax_date',
      is_account_book_registered:'is_account_book_registered',
      bank:'bank',
      bank_account:'bank_account',
      bank_account_owner:'bank_account_owner',
      note:'note',
      paytype:'paytype',
      status:'status'
    };

    return (
      <Modal show={this.props.isOpen} onHide={() => this.props.onClose(false)}>
        <Modal.Header closeButton>
          <Modal.Title>This is Title</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>{JSON.stringify(this.props.data)}</p>
        </Modal.Body>
        <Modal.Footer>
          <Button onClick={() => this.props.onClose(true, data)}>Confirm</Button>
          <Button onClick={() => this.props.onClose(false)}>Close</Button>
        </Modal.Footer>
      </Modal>
    );
  }
}

export default EditModal;
