import React, { Component, PropTypes } from 'react';
import update from 'react-addons-update'
import { Table, Checkbox, Button } from 'react-bootstrap';
import SearchBar from './searchBar';
import PaymentRow from './paymentRow';
import PaymentModal from './paymentModal';

class PaymentTable extends React.Component {
  constructor(props) {
    super(props);

    if (this.props.selectProps && this.props.selectProps.selected) {
      this.selected = this.props.selectProps.selected.slice();
    } else {
      this.selected = [];
    }

    this.editingIndex = -1;

    this.state = {
      datas: this.props.datas,
      selected: this.selected.slice(),
      modalContent: undefined,
      showModal: false
    };

    this.keyName = undefined;
    for (let dataKey in this.props.schema) {
      if (this.props.schema[dataKey].isKey) {
        this.keyName = dataKey;
        break;
      }
    }
  }

  handleSelect(e, index) {
    const isChecked = e.currentTarget.checked;

    let result = true;
    if (this.props.selectProps && this.props.selectProps.onSelect) {
      const data = this.state.datas[index];
      result = this.props.selectProps.onSelect(isChecked, index, data);
    }

    if (typeof result === 'undefined' || result !== false) {
      if (isChecked) {
        this.selected.push(index);
      } else {
        this.selected = this.selected.filter(i => i!==index);
      }
      this.setState({
        selected: this.selected.slice()
      });
    }
  }

  handleSelectAll(e) {
    let isChecked = e.currentTarget.checked;

    let changed;
    if (isChecked) {
      changed = [];
      for (let i=0; i<this.state.datas.length; ++i) {
        if (i!==this.selected[i]) {
          changed.push(i);
        }
      }
    } else {
      changed = this.selected.slice();
    }

    let result = true;
    if (this.props.selectProps && this.props.selectProps.onSelectAll) {
      const datas = changed.map(index => {
        return this.state.datas[index];
      });
      result = this.props.selectProps.onSelectAll(isChecked, changed, datas);
    }

    if (typeof result === 'undefined' || result !== false) {
      if (isChecked) {
        this.selected = this.state.datas.map((data, i) => {return i});
      } else {
        this.selected = [];
      }
      this.setState({
        selected: this.selected.slice()
      });
    }
  }

  handleAdd() {
    let result = true;
    if (this.props.updateProps && this.props.updateProps.onAdd) {
      result = this.props.updateProps.onAdd();
    }

    if (typeof result === 'undefined' || result !== false) {
      this.openModal('add');
    }
  }

  handleEdit(index) {
    let result = true;
    if (this.props.updateProps && this.props.updateProps.onEdit) {
      result = this.props.updateProps.onEdit(index, this.state.datas[index]);
    }

    if (typeof result === 'undefined' || result !== false) {
      this.editingIndex = index;
      this.openModal('edit');
    }
  }

  handleDel(index) {
    let result = true;
    if (this.props.updateProps && this.props.updateProps.onDel) {
      result = this.props.updateProps.onDel(index, this.state.datas[index]);
    }

    if (typeof result === 'undefined' || result !== false) {
      this.selected = update(this.selected, {$splice: [[this.selected.indexOf(index), 1]]});
      this.setState({
        datas: update(this.state.datas, {$splice: [[index, 1]]}),
        selected: this.selected.slice()
      });
    }
  }

  handleMultiEdit() {
    if (this.selected.length == 0) {
      return;
    }

    let result = true;
    if (this.props.updateProps && this.props.updateProps.onMultiEdit) {
      const datas = this.selected.map(index => {
        return this.state.datas[index];
      });
      result = this.props.updateProps.onMultiEdit(this.selected, datas);
    }

    if (typeof result === 'undefined' || result !== false) {
      if (this.selected.length == 1) {
        this.editingIndex = this.selected[0];
        this.openModal('edit', this.state.datas[this.selected[0]]);
      }
    }
  }

  handleMultiDel() {
    if (this.selected.length == 0) {
      return;
    }

    let result = true;
    if (this.props.updateProps && this.props.updateProps.onMultiDel) {
      const datas = this.selected.map(index => {
        return this.state.datas[index];
      });
      result = this.props.updateProps.onMultiDel(this.selected, datas);
    }

    if (typeof result === 'undefined' || result !== false) {
      let newDatas = this.state.datas.filter((data, i) => {return this.selected.indexOf(i)==-1});
      this.selected = [];

      this.setState({
        datas: newDatas,
        selected: this.selected.slice()
      });
    }
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
        this.editingIndex = -1;
      }
    }

    this.setState({ showModal: false });
  }

  render() {
    let headers = Object.values(this.props.schema);
    headers = headers.map(function (headerInfo, index) {
      return <th type={headerInfo.type? headerInfo.type : 'string'}
                 key={index}>{headerInfo.name}</th>;
    });

    let rows = [];
    this.state.datas.forEach((data, index) => {
      rows.push(<PaymentRow key={data[this.keyName]}
                            data={data}
                            selected={this.state.selected.indexOf(index) !== -1}
                            onSelect={e => this.handleSelect(e, index)}
                            onEdit={() => {this.handleEdit(index)}}
                            onDel={() => {this.handleDel(index)}}/>);
    });

    return (
      <div>
        <SearchBar/>

        <Table>
          <thead>
            <tr>
              <th><Checkbox onChange={e => this.handleSelectAll(e)}/></th>
              {headers}
            </tr>
          </thead>
          <tbody>
              {rows}
          </tbody>
        </Table>

        <Button onClick={this.handleMultiEdit.bind(this)}>편집</Button>
        <Button onClick={this.handleMultiDel.bind(this)}>삭제</Button>
        <Button onClick={this.handleAdd.bind(this)}>추가</Button>

        <PaymentModal data={this.state.modalContent}
                      isOpen={this.state.showModal}
                      onClose={this.handleCloseModal.bind(this)}/>
      </div>
    );
  }
}

PaymentTable.propTypes = {
  schema: PropTypes.object,
  datas: PropTypes.array,
  selectProps: PropTypes.shape({
    selected: PropTypes.array,
    onSelect: PropTypes.func,
    onSelectAll: PropTypes.func
  }),
  updateProps: PropTypes.shape({
    onAdd: PropTypes.func,
    onEdit: PropTypes.func,
    onDel: PropTypes.func,
    onMultiEdit:PropTypes.func,
    onMultiDel: PropTypes.func
  })
};

PaymentTable.defaultProps = {
  schema: {},
  datas: [],
  selectProps: {
    selected: [],
    onSelect: undefined,
    onSelectAll: undefined
  },
  updateProps: {
    onAdd: undefined,
    onEdit: undefined,
    onDel: undefined,
    onMultiEdit: undefined,
    onMultiDel: undefined
  }
};

export default PaymentTable;
