import React from 'react';
import SearchTable from './SearchSelectTable/SearchSelectTable'

class PaymentTable extends React.Component {
  handleSelect(isSelected, index, data) {
    console.log('handleSelect:', isSelected, index, data);
    console.log('now select:', this.selected);
  }

  handleSelectAll(isSelected, indexes, datas) {
    console.log('handleSelectAll:', isSelected, indexes, datas);
    console.log('now select:', this.selected);
  }

  handleAdd() {
    console.log('handleAdd');
    return true;
  }

  handleEdit(index, data) {
    console.log('handleEdit', data, index);
    return true;
  }

  handleDel(index, data) {
    console.log('handleDel', data, index);
    return true;
  }

  handleMultiEdit(indexes, datas) {
    console.log('handleMultiEdit', indexes, datas);
    return true;
  }

  handleMultiDel(indexes, datas) {
    console.log('handleMultiDel', indexes, datas);
    return true;
  }

  render() {
    return (
      <div>
        <SearchTable schema={this.props.schema}
                      datas={this.props.datas}
                      selectProps={{
                        selected: [],
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
      </div>
    );
  }
}

export default PaymentTable;
