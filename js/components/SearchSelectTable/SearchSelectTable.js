import React, { Component, PropTypes } from 'react';
import { Table, Checkbox, Button } from 'react-bootstrap';

import SearchBar from './SearchBar';
import TableRow from './TableRow';
import EditModal from './containers/EditModal';

const SearchSelectTable = ({
  schema,
  datas,

  selected,
  onSelect,
  onSelectAll,

  onAdd,
  onEdit,
  onDel,
  onMultiEdit,
  onMultiDel
}) => {

  let keyName = undefined;
  for (let dataKey in schema) {
    if (schema[dataKey].isKey) {
      keyName = dataKey;
      break;
    }
  }

  let headers = Object.values(schema);
  headers = headers.map(function (headerInfo, index) {
    return <th type={headerInfo.type? headerInfo.type : 'string'}
               key={index}>{headerInfo.name}</th>;
  });

  let rows = [];
  datas.forEach((data, index) => {
    rows.push(<TableRow key={data[keyName]}
                        data={data}
                        selected={selected? (selected.indexOf(index) !== -1) : false}
                        onSelect={e => onSelect(e, index)}
                        onEdit={() => onEdit(index)}
                        onDel={() => onDel(index)}/>);
  });

  return (
    <div>
      <SearchBar/>

      <Table>
        <thead>
        <tr>
          <th><Checkbox onChange={e => onSelectAll(e)}/></th>
          {headers}
        </tr>
        </thead>
        <tbody>
        {rows}
        </tbody>
      </Table>

      <Button onClick={onMultiEdit}>편집</Button>
      <Button onClick={onMultiDel}>삭제</Button>
      <Button onClick={onAdd}>추가</Button>

      <EditModal data={{}}/>
    </div>
  );
};


SearchSelectTable.propTypes = {
  schema: PropTypes.object,
  datas: PropTypes.array,
  //selectProps: PropTypes.shape({
    selected: PropTypes.array,
    onSelect: PropTypes.func,
    onSelectAll: PropTypes.func,
  //}),
  //updateProps: PropTypes.shape({
    onAdd: PropTypes.func,
    onEdit: PropTypes.func,
    onDel: PropTypes.func,
    onMultiEdit:PropTypes.func,
    onMultiDel: PropTypes.func
  //})
};

SearchSelectTable.defaultProps = {
  schema: {},
  datas: [],
  //selectProps: {
    selected: [],
    onSelect: undefined,
    onSelectAll: undefined,
  //},
  //updateProps: {
    onAdd: undefined,
    onEdit: undefined,
    onDel: undefined,
    onMultiEdit: undefined,
    onMultiDel: undefined
  //}
};

export default SearchSelectTable;
