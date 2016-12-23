import React from 'react';
import { Button, Modal } from 'react-bootstrap';

class EditModal extends React.Component {
  render() {
    const title = this.props.editMode == 'add' ? '추가' : '편집';

    return (
      <Modal show={this.props.isOpen} onHide={() => this.props.onClose(false)}>
        <Modal.Header closeButton>
          <Modal.Title>{title}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>{JSON.stringify(this.props.data)}</p>
        </Modal.Body>
        <Modal.Footer>
          <Button onClick={() => this.props.onClose(true)}>Confirm</Button>
          <Button onClick={() => this.props.onClose(false)}>Close</Button>
        </Modal.Footer>
      </Modal>
    );
  }
};

export default EditModal;
