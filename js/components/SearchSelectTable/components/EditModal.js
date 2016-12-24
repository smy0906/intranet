import React from 'react';
import { Button, Modal, Form, FormGroup, FormControl, ControlLabel } from 'react-bootstrap';
import Datetime from 'react-datetime';

class EditModal extends React.Component {
  constructor(props) {
    super(props);

    this.dataDefines = props.dataDefines;
  }

  renderBody() {
    //return <p>{JSON.stringify(this.props.data)}</p>;

    let controls = this.dataDefines.map((define, i) => {
      let control;
      let value = this.props.data? this.props.data[define.dataField]: undefined;

      switch(define.type) {
        case 'select':
          let options = define.options.map((option, index) => {
            return <option key={index} value={option.value}>{option.name}</option>;
          });
          control = (
            <FormControl componentClass='select' value={value}>
              {options}
            </FormControl>
          );
          break;

        case 'date':
          control = (<Datetime value={value} dateFormat='YYYY-MM-DD' timeFormat={false}/>);
          break;

        case 'text':
        default:
          control = (
            <FormControl type="text" value={value}/>
          );
      }

      return (
        <FormGroup key={i}>
          <ControlLabel>{define.name}</ControlLabel>
          {control}
        </FormGroup>
      );
    });

    return (
      <Form>
        {controls}
      </Form>
    );
  }

  render() {
    const title = this.props.editMode == 'add' ? '추가' : '편집';

    return (
      <Modal show={this.props.isOpen} onHide={() => this.props.onClose(false)}>
        <Modal.Header closeButton>
          <Modal.Title>{title}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {this.renderBody()}
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
