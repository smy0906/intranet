import React from 'react';
import update from 'react-addons-update'
import PaymentTable from './paymentTable'
import PaymentModal from './paymentModal';

class App extends React.Component {
  constructor(props) {
    super(props);

    this.modalAction = undefined;
    this.editingIndex = -1;

    this.selected = {};

    this.state = {
      datas: this.props.datas,
      //selected: {},
      modalContent: undefined,
      showModal: false
    };
  }

  handleSelect(isSelected, index, data) {
    // this.setState({
    //   selected: update(this.state.selected, {[index]:{$set: isSelected}})
    // });

    if (isSelected) {
      this.selected[index] = isSelected;
    } else {
      delete this.selected[index];
    }

    console.log('isSelected:', isSelected, index, data);
  }

  handleSelectAll(isSelected, indexes, datas) {
    // let newSelected = {};
    // for (let i=0; i<this.state.datas.length; ++i) {
    //   newSelected[i] = isSelected;
    // }
    //
    // this.setState({selected: update(this.state.selected, {$set: newSelected})});

    for (let i=0; i<indexes.length; ++i) {
      if (isSelected) {
        this.selected[indexes[i]] = isSelected;
      } else {
        delete this.selected[indexes[i]];
      }
    }

    console.log('isSelected:', isSelected, indexes, datas);
  }

  handleAdd() {
    console.log('onTableAdd');
    this.openModal('add');
  }

  handleEdit(index, data) {
    console.log('handleEdit', data, index);

    this.editingIndex = index;
    this.openModal('edit', data);
  }

  handleDel(index, data) {
    console.log('handleDel', data, index);
    console.log('todo: delete data:', data);

    this.setState({
      datas: update(this.state.datas, {$splice: [[index, 1]]})
    });
  }

  handleMultiEdit(indexes, datas) {
    console.log('onTableEdit', datas);

    if (datas.length == 1) {
      this.editingIndex = indexes[0];
      this.openModal('edit', datas[0]);
    }
  }

  handleMultiDel(indexes, datas) {
    console.log('onTableDel', datas);
    console.log('todo: delete data:', datas);

    let splices = [];
    for (let i=0; i<indexes.length; ++i) {
      splices.push([indexes[i], 1]);
    }

    this.setState({
      datas: update(this.state.datas, {$splice: splices})
    });

    // let splices = [];
    // let updates = {};
    // for (let i=0; i<indexes.length; ++i) {
    //   splices.push([indexes[i], 1]);
    //   updates[indexes[i]] = {$set: false};
    // }
    //
    // this.setState({
    //   datas: update(this.state.datas, {$splice: splices}),
    //   selected: update(this.state.selected, updates)
    // });
  }

  openModal(action, data) {
    this.modalAction = action;

    this.setState({
      modalContent: data,
      showModal: true
    });
  }

  handleCloseModal(isConfirmed, editedData) {
    if (isConfirmed) {
      if (this.modalAction == 'add') {
        console.log('todo: add data', editedData);
        this.setState({
          datas: update(this.state.datas, {$push: [editedData]})
        });

      } else if (this.modalAction == 'edit') {
        console.log('todo: edit data', editedData);
        this.setState({
          datas: update(this.state.datas, {[this.editingIndex]: {$set: editedData}})
        });
      }
    }

    this.setState({ showModal: false });
  }

  render() {
    // let selected = [];
    // for (let index in this.state.selected) {
    //   if (this.state.selected[index]) {
    //     selected.push(index);
    //   }
    // }

    return (
      <div>
        <PaymentTable schema={this.props.schema}
                      datas={this.state.datas}
                      selectProps={{
                        //selectedRows: this.state.selected,
                        onSelect: this.handleSelect.bind(this),
                        onSelectAll: this.handleSelectAll.bind(this)
                      }}
                      updateProps={{
                        onAdd: this.handleAdd.bind(this),
                        onEdit: this.handleEdit.bind(this),
                        onDel: this.handleDel.bind(this),
                        onMultiEdit: this.handleMultiEdit.bind(this),
                        onMultiDel: this.handleMultiDel.bind(this),
                      }}/>

        <PaymentModal data={this.state.modalContent}
                      isOpen={this.state.showModal}
                      onClose={this.handleCloseModal.bind(this)}/>
      </div>
    );
  }
}

export default App;
