import React from 'react';
import update from 'react-addons-update'
import PaymentTable from './paymentTable'
import PaymentModal from './paymentModal';

class App extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      datas: this.props.datas,
      modalContent: undefined,
      showModal: false
    };
  }

  onTableAdd() {
    console.log('onTableAdd');
    this.openModal('add');
  }

  onTableEdit(selects) {
    console.log('onTableEdit', selects);
    if (selects.length == 1) {
      this.openModal('edit', selects[0]);
    }
  }

  onTableDel(selects) {
    console.log('onTableDel', selects);
    //todo: update data
    let splices = [];
    for (let i=0; i<selects.length; ++i) {
      splices.push([selects[i], 1]);
    }
    this.setState({
      datas: update(this.state.datas, {$splice: splices})
    });
  }

  openModal(action, dataIndex) {
    this.modalAction = action;
    this.editingIndex = dataIndex;

    let content = undefined;
    if (dataIndex == undefined) {
      content = {};
    } else {
      content = this.state.datas[dataIndex];
    }

    this.setState({
      modalContent: content,
      showModal: true
    });
  }

  onCloseModal(isConfirmed, editedData) {
    if (isConfirmed) {
      if (this.modalAction == 'add') {
        //todo: update data
        this.setState({
          datas: update(this.state.datas, {$push: [editedData]})
        });

      } else if (this.modalAction == 'edit') {
        //todo: update data
        this.setState({
          datas: update(this.state.datas, {[this.editingIndex]: {$set: editedData}})
        });
      }
    }

    this.setState({ showModal: false });
  }

  render() {
    return (
      <div>
        <PaymentTable schema={this.props.schema}
                      datas={this.state.datas}
                      onAdd={this.onTableAdd.bind(this)}
                      onEdit={this.onTableEdit.bind(this)}
                      onDel={this.onTableDel.bind(this)}/>

        <PaymentModal data={this.state.modalContent}
                      isOpen={this.state.showModal}
                      onClose={this.onCloseModal.bind(this)}/>
      </div>
    );
  }
}

export default App;
