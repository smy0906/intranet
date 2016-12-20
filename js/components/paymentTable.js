import React from 'react';
import update from 'react-addons-update'
import { Table, Checkbox, Button } from 'react-bootstrap';
import SearchBar from './searchBar';
import PaymentRow from './paymentRow';

class PaymentTable extends React.Component {
  constructor(props) {
    super(props);

    // this.isAllSelected = false;
    // this.state = {
    //   selects: {}
    // };

    for (let dataKey in this.props.schema) {
      if (this.props.schema[dataKey].isKey) {
        this.keyName = dataKey;
        break;
      }
    }

    // this.selected = {};
    // if (this.props.selectProps && this.props.selectProps.selectedRows) {
    //   for (let i = 0; i < this.props.selectProps.selectedRows.length; ++i) {
    //     this.selected[this.props.selectProps.selectedRows[i]] = true;
    //   }
    // }
  }

  selectVal(index) {
    //return this.state.selects[index]? this.state.selects[index] : false;
    //return this.props.selectedRows? (this.props.selectedRows[index]? this.props.selectedRows[index] : false) : false;
    return this.selected[index]? this.selected[index] : false;
  }

  handleSelect(e, index) {
    if (!this.props.selectProps || !this.props.selectProps.onSelect) {
      return;
    }

    // this.setState({
    //   selects: update(this.state.selects, {[index]:{$set:!this.selectVal(index)}})
    // });
    const isChecked = e.currentTarget.checked;
    const data = this.props.datas[index];
    this.props.selectProps.onSelect(isChecked, index, data);
  }

  handleSelectAll(e) {
    // this.isAllSelected = !this.isAllSelected;
    //
    // if (this.isAllSelected) {
    //   let selects = {};
    //   for (let i=0; i<this.props.datas.length; ++i) {
    //     selects[i] = true;
    //   }
    //   this.setState({selects: selects});
    //
    // } else {
    //   this.setState({selects: {}});
    //
    // }

    let isChecked = e.currentTarget.checked;

    // if (isChecked) {
    //   let selects = {};
    //   for (let i=0; i<this.props.datas.length; ++i) {
    //     selects[i] = true;
    //   }
    //   this.setState({selects: selects});
    //
    // } else {
    //   this.setState({selects: {}});
    //
    // }

    let indexes;
    if (isChecked) {
      indexes = [];
      for (let i=0; i<this.props.datas.length; ++i) {
        indexes.push(i);
      }
    } else {
      indexes = this.getNowSelected();
    }

    const selectedDatas = indexes.map(index => {
      return this.props.datas[index];
    });

    if (this.props.selectProps && this.props.selectProps.onSelectAll) {
      this.props.selectProps.onSelectAll(isChecked, indexes, selectedDatas)
    }
  }

  handleAdd() {
    if (!this.props.updateProps || !this.props.updateProps.onAdd) {
      return;
    }

    this.props.updateProps.onAdd();
  }

  handleEdit(index) {
    if (!this.props.updateProps || !this.props.updateProps.onEdit) {
      return;
    }

    let data = this.props.datas[index];
    this.props.updateProps.onEdit(data);
  }

  handleDel(index) {
    if (!this.props.updateProps || !this.props.updateProps.onDel) {
      return;
    }

    this.props.updateProps.onDel(index, this.props.datas[index]);
  }

  handleMultiEdit() {
    if (!this.props.updateProps || !this.props.updateProps.onMultiEdit) {
      return;
    }

    let indexes = this.getNowSelected();
    const selectedDatas = indexes.map(index => {
      return this.props.datas[index];
    });

    this.props.updateProps.onMultiEdit(indexes, selectedDatas);

    // let selected = [];
    // for (let index in this.selected) {
    //   if (this.selected[index]) {
    //     selected.push(parseInt(index));
    //   }
    // };
    //
    // if (selected.length > 0) {
    //   this.props.updateProps.onMultiEdit(selected);
    // }
  }

  handleMultiDel() {
    if (!this.props.updateProps || !this.props.updateProps.onMultiDel) {
      return;
    }

    let indexes = this.getNowSelected();
    const selectedDatas = indexes.map(index => {
      return this.props.datas[index];
    });

    this.props.updateProps.onMultiDel(indexes, selectedDatas);

    // let selected = [];
    // for (let index in this.selected) {
    //   if (this.selected[index]) {
    //     selected.push(parseInt(index));
    //   }
    // };
    //
    // if (selected.length > 0) {
    //   this.props.updateProps.onMultiDel(selected);
    // }
  }

  // componentWillReceiveProps(nextProps) {
  //   let selects = {};
  //   for (let i=0; i<nextProps.datas.length; ++i) {
  //     selects[i] = this.selectVal(i);
  //   }
  //   this.state.selects = selects;
  // }

  render() {
    let headers = Object.values(this.props.schema);
    headers = headers.map(function (headerInfo, index) {
      return <th type={headerInfo.type? headerInfo.type : 'string'}
                 key={index}>{headerInfo.name}</th>;
    });

    let rows = [];
    this.props.datas.forEach((data, index) => {
      rows.push(<PaymentRow key={data[this.keyName]}
                            data={data}
                            //selected={this.selectVal(index)}
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
      </div>
    );
  }
}

export default PaymentTable;
